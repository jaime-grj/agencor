import requests
from bs4 import BeautifulSoup
import mysql.connector
from dotenv import load_dotenv
import re
from datetime import datetime, date, time
import calendar
import traceback
import unicodedata
import os

load_dotenv()

DB_HOST = os.getenv("DB_HOST")
DB_USER = os.getenv("DB_USER")
DB_PASSWORD = os.getenv("DB_PASSWORD")
DB_NAME = os.getenv("DB_NAME")
IMAGE_DIR = os.getenv("IMAGE_DIR")

class Event:
    def __init__(self, title, url, image, date_start, date_end, min_price, max_price, description):
        self.title = title
        self.url = url
        self.image = image
        self.date_start = date_start
        self.date_end = date_end
        self.min_price = min_price
        self.max_price = max_price
        self.description = description

    def __str__(self):
        return f"Titulo: {self.title}\nURL: {self.url}\nIMG: {self.image}\nFecha inicio: {self.date_start}\nFecha fin: {self.date_end}\nPrecio: {self.min_price} - {self.max_price}"


MONTHS = {
    "enero": 1, "febrero": 2, "marzo": 3, "abril": 4,
    "mayo": 5, "junio": 6, "julio": 7, "agosto": 8,
    "septiembre": 9, "octubre": 10, "noviembre": 11, "diciembre": 12
}

def get_month_num(month_str):
    month_str = month_str.lower().strip()
    for name, number in MONTHS.items():
        if month_str.startswith(name[:3]):  # allows "jun", "juni", "junio"
            return number
    return None

def extract_dates(text):
    text = text.lower()
    text = unicodedata.normalize('NFC', text)
    current_year = datetime.now().year

    # 1. day range: "del 6 al 11 de junio 2025", "3 al 6 junio 2025"
    match = re.search(r"\.\s?(?:del\s*)?(\d{1,2})\s*(?:al|–|-)\s*(\d{1,2})(?:\s+d?e?\s?+)?(\w+)(?:\s+(\d{4}))?", text, re.IGNORECASE)
    if match:
        d1, d2, month, year = match.groups()
        month_num = get_month_num(month)
        if month_num:
            year = int(year) if year else current_year
            return date(year, month_num, int(d1)), date(year, month_num, int(d2))

    # 2. hyphen: "5-6 junio 2025"
    match = re.search(r"\.\s?(\d{1,2})[-–](\d{1,2})\s+(\w+)(?:\s+(\d{4}))?", text, re.IGNORECASE)
    if match:
        d1, d2, month, year = match.groups()
        month_num = get_month_num(month)
        if month_num:
            year = int(year) if year else current_year
            return date(year, month_num, int(d1)), date(year, month_num, int(d2))

    # 3. one day: "12 de junio 2025", "7 junio 2025"
    match = re.search(r"\.\s?(?:lunes|martes|miercoles|miércoles|jueves|viernes|sabado|sábado|domingo)?\s*(\d{1,2})\s+d?e?\s?+(\w+)(?:\s+(\d{4}))?", text, re.IGNORECASE)
    if match:
        dia, month, year = match.groups()
        month_num = get_month_num(month)
        if month_num:
            year = int(year) if year else current_year
            return date(year, month_num, int(dia)), None

    # 4. month range: "junio-julio 2025", "abril-mayo-junio 2025"
    match = re.search(r"\.\s?((?:\w+-)+\w+)\s+(\d{4})", text, re.IGNORECASE)
    if match:
        months_raw, year = match.groups()
        months = months_raw.split("-")
        month_start = get_month_num(months[0])
        month_end = get_month_num(months[-1])
        if month_start and month_end:
            year = int(year)
            day_end = calendar.monthrange(year, month_end)[1]
            return date(year, month_start, 1), date(year, month_end, day_end)

    # 5. full month: "junio 2025"
    match = re.search(r"\.\s?(\w+)\s+(\d{4})", text, re.IGNORECASE)
    if match:
        month, year = match.groups()
        month_num = get_month_num(month)
        if month_num:
            year = int(year)
            day_end = calendar.monthrange(year, month_num)[1]
            return date(year, month_num, 1), date(year, month_num, day_end)

    return None, None

def extract_date(text):
    match = re.search(
        r"(?:lunes|martes|miercoles|miércoles|jueves|viernes|sabado|sábado|domingo)?\s*(\d{1,2})\s+d?e?\s?+(\w+)(?:\s+(\d{4}))?",
        text,
        re.IGNORECASE
    )
    if match:
        day_str, month_str, year_str = match.groups()
        day = int(day_str)
        month_num = get_month_num(month_str)
        if month_num:
            year = int(year_str) if year_str else datetime.now().year
            try:
                return date(year, month_num, day)
            except ValueError:
                return None  # Invalid day for given month
    return None

def extract_prices(description):
    if description:
        prices = []
        min_price = None
        max_price = None
        lines = description.splitlines()
        for i, line in enumerate(lines):
            # Check if line contains Entradas or Localidades
            if re.search(r'\b(?:Entradas|Localidades|Entrada general)\b', line, re.IGNORECASE):
                # Collect this line and the next line (if exists)
                combined = line
                if i + 1 < len(lines):
                    combined += " " + lines[i + 1]
                    print(combined)

                matches = re.findall(r'(\d+)(?:\s?€)?', combined, re.IGNORECASE)

                for match in matches:
                    prices.extend(int(n) for n in matches)

        if prices:
            prices = sorted(set(prices))
            if len(prices) == 1:
                min_price = prices[0]
                max_price = None
            else:
                min_price = prices[0]
                max_price = prices[-1]
        else:
            min_price = max_price = None

        return min_price, max_price 
    else:
        return None, None


def download_image(image_url):
    try:
        filename = image_url.split("/")[-1]
        filepath = os.path.join(IMAGE_DIR, filename)
        if os.path.exists(filepath):
            print(f"Image already exists: {filepath}")
            return filename
        response = requests.get(image_url)
        response.raise_for_status()
        with open(filepath, "wb") as f:
            f.write(response.content)
        print(f"Downloaded image: {filepath}")
        return filename
    except Exception as e:
        print(f"Image download failed: {e}")
        return None

def extract_events(raw_events, day_text=None, seen_titles=None):
    seen_titles = seen_titles or set()
    events = []

    for event in raw_events:
        try:
            link = event.find("a", class_="_self")
            title_tag = event.find(class_="pt-cv-title")
            title = title_tag.find("a").text.strip() if title_tag else None
            if not title or title in seen_titles:
                continue
            seen_titles.add(title)

            url = link["href"]
            image_tag = event.find("img")
            image_url = image_tag["src"] if image_tag else None
            date_start, date_end = extract_dates(title)
            if not date_start and day_text:
                date_start = extract_date(day_text)

            response = requests.get(url)
            response.raise_for_status()
            soup = BeautifulSoup(response.text, "html.parser")
            text_inner = soup.find("div", class_="et_pb_text_inner")
            description = text_inner.get_text(strip=True, separator="\n") if text_inner else ""
            description += "\n\nObtenido automáticamente de Teleagenda Córdoba.\n"

            min_price, max_price = extract_prices(description)
            image_filename = download_image(image_url) if image_url else None
            event_obj = Event(title, url, image_filename, date_start, date_end, min_price, max_price, description)
            events.append(event_obj)
            print(event_obj)

        except Exception:
            traceback.print_exc()

    return events

def fetch_teleagenda_events():
    events, titles = [], set()

    soup = BeautifulSoup(requests.get("https://teleagenda.cordoba.es").text, "html.parser")
    for day in soup.find_all("div", class_="et_pb_column_inner"):
        day_text = day.find(class_="et-box-content")
        raw_events = day.find_all("div", class_="pt-cv-content-item")
        events += extract_events(raw_events, day_text.get_text(strip=True) if day_text else None, titles)

    soup = BeautifulSoup(requests.get("https://teleagenda.cordoba.es/proximamente-en-cordoba/").text, "html.parser")
    raw_events = soup.find_all("div", class_="pt-cv-content-item")
    events += extract_events(raw_events, None, titles)

    return events


def insert_events_to_db(events):
    try:
        conn = mysql.connector.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASSWORD,
            database=DB_NAME
        )
        cursor = conn.cursor()

        insert_query = """
            INSERT INTO events (title, url, media_filename, datetime_start, datetime_end, min_price, max_price, long_description)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        select_query = "SELECT 1 FROM events WHERE title = %s"

        for event in events:
            try:
                cursor.execute(select_query, (event.title,))
                if cursor.fetchone():
                    print(f"Already in DB: {event.title}")
                    continue

                datetime_start = datetime.combine(event.date_start, time(0, 0)) if event.date_start else None
                datetime_end = datetime.combine(event.date_end, time(23, 59)) if event.date_end else None

                cursor.execute(insert_query, (
                    event.title, event.url, event.image,
                    datetime_start, datetime_end, event.min_price, event.max_price, event.description
                ))
                conn.commit()
                print(f"Inserted: {event.title}")

            except Exception as db_err:
                print(f"DB error: {db_err}")
                conn.rollback()

        cursor.close()
        conn.close()
    except mysql.connector.Error as e:
        print(f"Database connection error: {e}")


if __name__ == "__main__":
    events = fetch_teleagenda_events()
    insert_events_to_db(events)
