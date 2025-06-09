// To parse this JSON data, do
//
//     final eventPage = eventPageFromJson(jsonString);

import 'dart:convert';

EventPage eventPageFromJson(String str) => EventPage.fromJson(json.decode(str));

String eventPageToJson(EventPage data) => json.encode(data.toJson());

class EventPage {
  final int currentPage;
  final List<Event> data;
  final String firstPageUrl;
  final int from;
  final int lastPage;
  final String lastPageUrl;
  final List<Link> links;
  final String nextPageUrl;
  final String path;
  final int perPage;
  final dynamic prevPageUrl;
  final int to;
  final int total;

  EventPage({
    required this.currentPage,
    required this.data,
    required this.firstPageUrl,
    required this.from,
    required this.lastPage,
    required this.lastPageUrl,
    required this.links,
    required this.nextPageUrl,
    required this.path,
    required this.perPage,
    required this.prevPageUrl,
    required this.to,
    required this.total,
  });

  factory EventPage.fromJson(Map<String, dynamic> json) => EventPage(
        currentPage: json["current_page"],
        data: List<Event>.from(json["data"].map((x) => Event.fromJson(x))),
        firstPageUrl: json["first_page_url"],
        from: json["from"],
        lastPage: json["last_page"],
        lastPageUrl: json["last_page_url"],
        links: List<Link>.from(json["links"].map((x) => Link.fromJson(x))),
        nextPageUrl: json["next_page_url"],
        path: json["path"],
        perPage: json["per_page"],
        prevPageUrl: json["prev_page_url"],
        to: json["to"],
        total: json["total"],
      );

  Map<String, dynamic> toJson() => {
        "current_page": currentPage,
        "data": List<dynamic>.from(data.map((x) => x.toJson())),
        "first_page_url": firstPageUrl,
        "from": from,
        "last_page": lastPage,
        "last_page_url": lastPageUrl,
        "links": List<dynamic>.from(links.map((x) => x.toJson())),
        "next_page_url": nextPageUrl,
        "path": path,
        "per_page": perPage,
        "prev_page_url": prevPageUrl,
        "to": to,
        "total": total,
      };
}

class Event {
  final int id;
  final String title;
  final dynamic shortDescription;
  final String longDescription;
  final dynamic startDate;
  final dynamic endDate;
  final int? capacity;
  final double? minPrice;
  final double? maxPrice;
  final dynamic isTimeSet;
  final dynamic mediaFilename;
  final dynamic createdAt;
  final dynamic updatedAt;
  final String? location;
  final dynamic url;
  final String? mediaAlt;
  final String? webUrl;

  Event({
    required this.id,
    required this.title,
    this.shortDescription,
    required this.longDescription,
    this.startDate,
    this.endDate,
    this.capacity,
    this.minPrice,
    this.maxPrice,
    this.isTimeSet,
    this.mediaFilename,
    this.createdAt,
    this.updatedAt,
    this.location,
    this.url,
    this.mediaAlt,
    this.webUrl
  });

  factory Event.fromJson(Map<String, dynamic> json) => Event(
        id: json["id"],
        title: json["title"],
        shortDescription: json["short_description"],
        longDescription: json["long_description"],
        startDate: DateTime.parse(json["datetime_start"]),
        endDate: DateTime.parse(json["datetime_end"]),
        capacity: json["capacity"],
        minPrice: json["min_price"],
        maxPrice: json["max_price"],
        isTimeSet: json["is_time_set"],
        location: json["location"],
        mediaFilename: json["media_filename"],
        createdAt: json["created_at"],
        updatedAt: json["updated_at"],
        url: json["url"],
        mediaAlt: json["media_alt"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "title": title,
        "short_description": shortDescription?.toString(),
        "long_description": longDescription,
        "datetime_start": startDate?.toIso8601String(),
        "datetime_end": endDate?.toIso8601String(),
        "capacity": capacity,
        "min_price": minPrice,
        "max_price": maxPrice,
        "is_time_set": isTimeSet,
        "media_filename": mediaFilename,
        "created_at": createdAt,
        "updated_at": updatedAt,
        "location": location,
        "url": url,
        "media_alt": mediaAlt,
      };
}

class Link {
  final String? url;
  final String label;
  final bool active;

  Link({
    required this.url,
    required this.label,
    required this.active,
  });

  factory Link.fromJson(Map<String, dynamic> json) => Link(
        url: json["url"],
        label: json["label"],
        active: json["active"],
      );

  Map<String, dynamic> toJson() => {
        "url": url,
        "label": label,
        "active": active,
      };
}
