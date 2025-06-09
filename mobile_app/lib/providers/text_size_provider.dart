import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class TextSizeProvider with ChangeNotifier {
  String _textSize = 'medium'; // Default

  String get textSize => _textSize;

  TextSizeProvider() {
    _loadPreferences();
  }

  Future<void> _loadPreferences() async {
    final prefs = await SharedPreferences.getInstance();
    _textSize = prefs.getString('textSize') ?? 'medium';
    notifyListeners();
  }


  double get scaleFactor {
    switch (_textSize) {
      case 'small':
        return 0.85;
      case 'large':
        return 1.5;
      case 'veryLarge':
        return 2;
      default:
        return 1.0;
    }
  }

  void setTextSize(String size) {
    _textSize = size;
    notifyListeners();
  }

  void loadFromPrefs(String size) {
    _textSize = size;
    notifyListeners();
  }
}