import 'dart:convert';
import 'package:agencor/models/category.dart';
import 'package:agencor/models/event_page.dart';
import 'package:http/http.dart' as http;
//import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  final String baseUrl = 'http://agencor.test:8000';
  String? apiUrl;
  String? storageUrl;
  var token = '';

  ApiService() {
    apiUrl = '$baseUrl/api/v1/';
    storageUrl = '$baseUrl/storage/images/';
  }

  _getToken() async {
    //SharedPreferences localStorage = await SharedPreferences.getInstance();
    //token = jsonDecode(localStorage.getString('token')!)['token'];
    token = 'abcd';
  }

  authData(data, apiAuthUrl) async {
    var fullUrl = apiUrl! + apiAuthUrl;
    return await http.post(Uri.parse(fullUrl),
        body: jsonEncode(data), headers: _setHeaders());
  }

  getData(apiDataUrl) async {
    var fullUrl = apiUrl! + apiDataUrl;
    await _getToken();
    return await http.get(Uri.parse(fullUrl), headers: _setHeaders());
  }

  postData(data, apiDataUrl) async {
    var fullUrl = apiUrl! + apiDataUrl;
    await _getToken();
    return await http.post(Uri.parse(fullUrl),
        body: jsonEncode(data), headers: _setHeaders());
  }

  _setHeaders() => {
        'Content-type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token'
      };

  String getApiUrl() {
    return apiUrl!;
  }

  String getStorageUrl() {
    return storageUrl!;
  }

  Future<List<Category>> getCategories() async {
    final response = await getData('categories');
    List<Category> categoriesList = [];
    if (response.statusCode == 200) {
      String body = utf8.decode(response.bodyBytes);
      final jsonData = jsonDecode(body);
      final categoryPageJson = jsonData["data"];
      final categoriesJson = categoryPageJson["data"];
      for (var category in categoriesJson) {
        categoriesList.add(Category(
            id: category["id"],
            name: category["name"],
            description: category["description"]));
      }
    } else {
      throw Exception('Failed to load categories');
    }
    return categoriesList;
  }

  Future<List<Event>> getEvents(int page) async {
    final response = await getData('events?page=$page');
    List<Event> eventsList = [];
    if (response.statusCode == 200) {
      String body = utf8.decode(response.bodyBytes);

      final jsonData = jsonDecode(body);
      final eventPageJson = jsonData["data"];
      final eventsJson = eventPageJson["data"];

      for (var event in eventsJson) {
        eventsList.add(Event(
            id: event["id"],
            title: event["title"],
            shortDescription: event["short_description"],
            longDescription: event["long_description"],
            startDate: event["datetime_start"],
            endDate: event["datetime_end"],
            isTimeSet: event["is_time_set"],
            capacity: event["capacity"],
            mediaFilename: event["media_filename"],
            mediaAlt: event["media_alt"],
            location: event["location"],
            minPrice: event["min_price"] != null ? (event["min_price"] as num).toDouble() : null,
            maxPrice: event["max_price"] != null ? (event["max_price"] as num).toDouble() : null,
            url: event["url"],
            webUrl: '$baseUrl/event/${event["id"]}',
          )
        );
      }
    } else {
      throw Exception('Failed to load events');
    }

    return eventsList;
  }

  Future<List<Event>> getEventsByCategory(int categoryId, int page) async {
    final response = await getData('events/category/$categoryId?page=$page');
    List<Event> eventsList = [];

    if (response.statusCode == 200) {
      String body = utf8.decode(response.bodyBytes);

      final jsonData = jsonDecode(body);
      final eventPageJson = jsonData["data"];
      final eventsJson = eventPageJson["data"];

      for (var event in eventsJson) {
        eventsList.add(Event(
            id: event["id"],
            title: event["title"],
            shortDescription: event["short_description"],
            longDescription: event["long_description"],
            startDate: event["datetime_start"],
            endDate: event["datetime_end"],
            isTimeSet: event["is_time_set"],
            capacity: event["capacity"],
            mediaFilename: event["media_filename"],
            location: event["location"],
            minPrice: event["min_price"] != null ? (event["min_price"] as num).toDouble() : null,
            maxPrice: event["max_price"] != null ? (event["max_price"] as num).toDouble() : null,
            url: event["url"]));
      }
    } else {
      throw Exception('Failed to load events');
    }

    return eventsList;
  }

  Future<List<Event>> getFeaturedEvents(int page) async {
    final response = await getData('events/featured');
    List<Event> eventsList = [];
    if (response.statusCode == 200) {
      String body = utf8.decode(response.bodyBytes);

      final jsonData = jsonDecode(body);
      final eventsJson = jsonData["data"];

      for (var event in eventsJson) {
        eventsList.add(Event(
            id: event["id"],
            title: event["title"],
            shortDescription: event["short_description"],
            longDescription: event["long_description"],
            startDate: event["datetime_start"],
            endDate: event["datetime_end"],
            isTimeSet: event["is_time_set"],
            capacity: event["capacity"],
            mediaFilename: event["media_filename"],
            location: event["location"],
            minPrice: event["min_price"] != null ? (event["min_price"] as num).toDouble() : null,
            maxPrice: event["max_price"] != null ? (event["max_price"] as num).toDouble() : null,
            url: event["url"]));
      }
    } else {
      throw Exception('Failed to load events');
    }

    return eventsList;
  }

  Future<List<Event>> getSearchResults(
      int page,
      String title,
      String? location,
      Map<String, String>? categories,
      DateTime? beforeDate,
      DateTime? afterDate,
      double? minPrice,
      double? maxPrice) async {
    final locationFilter = location != null ? '&location=$location' : '';
    final categoryFilter = categories!.isNotEmpty
        ? '&category[]=${categories.keys.join("&category[]=")}' // Send IDs in the query
        : '';
    final dateBeforeFilter =
        beforeDate != null ? '&before=${beforeDate.toIso8601String()}' : '';
    final dateAfterFilter =
        afterDate != null ? '&after=${afterDate.toIso8601String()}' : '';

    final minPriceFilter = minPrice != null ? '&min_price=$minPrice' : '';
    final maxPriceFilter = maxPrice != null ? '&max_price=$maxPrice' : '';

    final response = await getData(
        'search?title=$title&page=$page$locationFilter$categoryFilter$dateBeforeFilter$dateAfterFilter$minPriceFilter$maxPriceFilter');
    List<Event> eventsList = [];
    if (response.statusCode == 200) {
      String body = utf8.decode(response.bodyBytes);
      final jsonData = jsonDecode(body);
      final eventPageJson = jsonData["data"];
      final eventsJson = eventPageJson["data"];

      for (var event in eventsJson) {
        eventsList.add(Event(
            id: event["id"],
            title: event["title"],
            shortDescription: event["short_description"],
            longDescription: event["long_description"],
            startDate: event["datetime_start"],
            endDate: event["datetime_end"],
            isTimeSet: event["is_time_set"],
            capacity: event["capacity"],
            mediaFilename: event["media_filename"],
            mediaAlt: event["media_alt"],
            location: event["location"],
            minPrice: event["min_price"] != null ? (event["min_price"] as num).toDouble() : null,
            maxPrice: event["max_price"] != null ? (event["max_price"] as num).toDouble() : null,
            url: event["url"]));
      }
    } else {
      throw Exception('Failed to load events');
    }

    return eventsList;
  }
}
