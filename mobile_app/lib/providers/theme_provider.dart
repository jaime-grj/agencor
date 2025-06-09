import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ThemeProvider extends ChangeNotifier {
  ThemeMode _themeMode = ThemeMode.system;
  bool _isPureBlack = false;

  ThemeMode get themeMode => _themeMode;
  bool get isPureBlack => _isPureBlack;

  ThemeProvider() {
    _loadPreferences();
  }

  Future<void> _loadPreferences() async {
    final prefs = await SharedPreferences.getInstance();
    _isPureBlack = prefs.getBool('usePureBlack') ?? false;
    notifyListeners();
  }

  Future<void> setThemeMode(ThemeMode mode, {bool pureBlack = false}) async {
    _themeMode = mode;
    _isPureBlack = pureBlack;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('usePureBlack', _isPureBlack);
    notifyListeners();
  }

  Future<void> setPureBlack(bool pureBlack) async {
    _isPureBlack = pureBlack;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setBool('usePureBlack', _isPureBlack);
    if (_themeMode == ThemeMode.dark) {
      notifyListeners();
    }
  }
}