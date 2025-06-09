import 'package:agencor/providers/theme_provider.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:provider/provider.dart';
import 'package:flutter_gen/gen_l10n/app_localizations.dart';
import 'package:agencor/providers/text_size_provider.dart';
import 'package:agencor/providers/locale_provider.dart';

class SettingsPage extends StatefulWidget {
  const SettingsPage({Key? key}) : super(key: key);
  @override
  State<SettingsPage> createState() => _SettingsPageState();
}

class _SettingsPageState extends State<SettingsPage> {
  String _themeMode = 'system';
  bool _hideMapOnEventDetails = false;
  String? selectedTheme;
  String _textSize = 'medium';
  String _languageCode = 'system';
  bool _usePureBlack = false;
  bool _showWarningWhenOpeningLinks = true;
  String? selectedTextSize;

  @override
  void initState() {
    super.initState();
    _loadConfig();
  }

  // Load saved settings from SharedPreferences
  void _loadConfig() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _themeMode = prefs.getString('themeMode') ?? 'system';
      _languageCode = prefs.getString('languageCode') ?? 'system';
      _textSize = prefs.getString('textSize') ?? 'medium';
      _hideMapOnEventDetails = prefs.getBool('showMapOnEvents') ?? false;
      _usePureBlack = prefs.getBool('usePureBlack') ?? false;
      _showWarningWhenOpeningLinks = prefs.getBool('showWarningWhenOpeningLinks') ?? true;
    });
  }

  // Save settings to SharedPreferences
  void _saveConfig() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.setString('themeMode', _themeMode);
    prefs.setBool('hideMapOnEvents', _hideMapOnEventDetails);
    prefs.setString('textSize', _textSize);
    prefs.setString('languageCode', _languageCode);
    prefs.setBool('usePureBlack', _usePureBlack);
    prefs.setBool('showWarningWhenOpeningLinks', _showWarningWhenOpeningLinks);
  }

  // Apply theme based on the selected dark mode
  void _applyTheme() {
    ThemeProvider themeProvider =
        Provider.of<ThemeProvider>(context, listen: false);

    if (_themeMode == 'light') {
      themeProvider.setThemeMode(ThemeMode.light);
    } else if (_themeMode == 'system') {
      themeProvider.setThemeMode(ThemeMode.system, pureBlack: _usePureBlack);
    } else if (_themeMode == 'dark') {
      themeProvider.setThemeMode(ThemeMode.dark, pureBlack: _usePureBlack);
    } else {
      themeProvider.setThemeMode(ThemeMode.system, pureBlack: _usePureBlack);
    }
  }

  void _applySettings() {
    _applyTheme();
    Provider.of<TextSizeProvider>(context, listen: false).setTextSize(_textSize);
  }

  @override
  Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text(AppLocalizations.of(context)!.settingsMenu),
    ),
    body: Padding(
      padding: const EdgeInsets.all(0.0),
      child: SingleChildScrollView(
        child: Column(
          children: <Widget>[
            ListTile(
              title: Text(AppLocalizations.of(context)!.language),
              subtitle: Text(_getLanguageDisplayName(_languageCode)),
              onTap: _showLanguageDialog,
            ),
            ListTile(
              title: Text(AppLocalizations.of(context)!.theme),
              subtitle: Text(_getLocalizedThemeName(context)),
              onTap: _showRadioDialog,
            ),
            if (_themeMode == 'dark' || _themeMode == 'system')
              SwitchListTile(
                title: Text(AppLocalizations.of(context)!.usePureBlackTheme),
                subtitle: Text(AppLocalizations.of(context)!.pureBlackDescription),
                value: _usePureBlack,
                onChanged: (value) {
                  setState(() {
                    _usePureBlack = value;
                  });
                  _saveConfig();
                  _applyTheme();
                },
              ),
            ListTile(
              title: Text(AppLocalizations.of(context)!.textSize),
              subtitle: Text(_getLocalizedTextSizeName(context)),
              onTap: _showTextSizeDialog,
            ),
            SwitchListTile(
              title: Text(AppLocalizations.of(context)!.showWarningWhenOpeningLinks),
              subtitle: Text(AppLocalizations.of(context)!.showWarningWhenOpeningLinksSubtitle),
              value: _showWarningWhenOpeningLinks,
              onChanged: (value) {
                setState(() {
                  _showWarningWhenOpeningLinks = value;
                });
                _saveConfig();
              },
            ),
          ],
        ),
      ),
    ),
  );
  }

  // Function to show the dialog

  Future<void> _showRadioDialog() async {
    final localizations = AppLocalizations.of(context)!;
    final themeModeOptions = [
      localizations.systemTheme,
      localizations.lightTheme,
      localizations.darkTheme,
    ];

    await showDialog(
      context: context,
      builder: (BuildContext context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: Text(localizations.selectTheme),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: themeModeOptions.map((option) {
                  return RadioListTile<String>(
                    title: Text(option),
                    value: option,
                    groupValue: selectedTheme ??
                        _getLocalizedThemeName(
                            context), // Set default selected option to current theme
                    onChanged: (String? value) {
                      setState(() {
                        selectedTheme = value;
                      });
                    },
                  );
                }).toList(),
              ),
              actions: <Widget>[
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop(); // Close the dialog
                  },
                  child: Text(localizations.cancel),
                ),
                TextButton(
                  onPressed: () {
                    if (selectedTheme != null) {
                      setState(() {
                        _themeMode = _themeModeFromLocalizedOption(
                          selectedTheme!,
                          context,
                        );
                      });
                      _saveConfig(); // Save the selected theme
                      _applySettings(); // Apply the theme immediately
                    }
                    Navigator.of(context)
                        .pop(); // Close the dialog after applying
                  },
                  child: Text(localizations.confirm),
                ),
              ],
            );
          },
        );
      },
    );

    // After the dialog is dismissed, ensure the parent widget reflects the latest theme mode
    setState(() {
      _themeMode = _themeMode; // Trigger a rebuild of the parent widget
    });
  }

  Future<void> _showLanguageDialog() async {
    final localizations = AppLocalizations.of(context)!;

    final options = {
      'system': localizations.systemLanguage,
      'en': 'English',
      'es': 'Español',
    };

    String selected = _languageCode;

    await showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: Text(localizations.selectLanguage),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: options.entries.map((entry) {
                  return RadioListTile<String>(
                    title: Text(entry.value),
                    value: entry.key,
                    groupValue: selected,
                    onChanged: (value) {
                      setState(() {
                        selected = value!;
                      });
                    },
                  );
                }).toList(),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(context).pop(),
                  child: Text(localizations.cancel),
                ),
                TextButton(
                  onPressed: () async {
                    setState(() {
                      _languageCode = selected;
                    });

                    final prefs = await SharedPreferences.getInstance();
                    if (!mounted) return; // <-- Prevent using context if widget is disposed
                    if (_languageCode == 'system') {
                      prefs.remove('languageCode');
                      Provider.of<LocaleProvider>(context, listen: false).clearLocale();
                    } else {
                      prefs.setString('languageCode', _languageCode);
                      Provider.of<LocaleProvider>(context, listen: false).setLocale(Locale(_languageCode));
                    }

                    Navigator.of(context).pop();
                  },
                  child: Text(localizations.confirm),
                ),
              ],
            );
          },
        );
      },
    );

    setState(() {
      _languageCode = _languageCode; // Refresh UI
    });
  }


  // Get localized theme name based on current _themeMode
  String _getLocalizedThemeName(BuildContext context) {
    switch (_themeMode) {
      case 'light':
        return AppLocalizations.of(context)!.lightTheme;
      case 'dark':
        return AppLocalizations.of(context)!.darkTheme;
      default:
        return AppLocalizations.of(context)!.systemTheme;
    }
  }

  String _themeModeFromLocalizedOption(String option, BuildContext context) {
    final localizations = AppLocalizations.of(context)!;
    if (option == localizations.lightTheme) return 'light';
    if (option == localizations.darkTheme) return 'dark';
    return 'system';
  }

  Future<void> _showTextSizeDialog() async {
    final localizations = AppLocalizations.of(context)!;
    final options = [
      localizations.textSizeSmall,
      localizations.textSizeMedium,
      localizations.textSizeLarge,
      localizations.textSizeExtraLarge
    ];

    String current = _getLocalizedTextSizeName(context);
    String? selected = current;

    await showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              title: Text(localizations.selectTextSize),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: options.map((option) {
                  return RadioListTile<String>(
                    title: Text(option),
                    value: option,
                    groupValue: selected,
                    onChanged: (value) {
                      setState(() {
                        selected = value;
                      });
                    },
                  );
                }).toList(),
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(context).pop(),
                  child: Text(localizations.cancel),
                ),
                TextButton(
                  onPressed: () {
                    if (selected != null) {
                      setState(() {
                        _textSize = _textSizeFromLocalizedOption(selected!, context);
                      });
                      _applySettings();
                      _saveConfig();
                      // Optionally apply text scaling here
                    }
                    Navigator.of(context).pop();
                  },
                  child: Text(localizations.confirm),
                ),
              ],
            );
          },
        );
      },
    );
    setState(() {
      _textSize = _textSize; // Trigger a rebuild of the parent widget
    });
  }

  String _getLocalizedTextSizeName(BuildContext context) {
    final localizations = AppLocalizations.of(context)!;
    switch (_textSize) {
      case 'small':
        return localizations.textSizeSmall;
      case 'large':
        return localizations.textSizeLarge;
      case 'veryLarge':
        return localizations.textSizeExtraLarge;
      default:
        return localizations.textSizeMedium;
    }
  }

  String _textSizeFromLocalizedOption(String option, BuildContext context) {
    final localizations = AppLocalizations.of(context)!;
    if (option == localizations.textSizeSmall) return 'small';
    if (option == localizations.textSizeLarge) return 'large';
    if (option == localizations.textSizeExtraLarge) return 'veryLarge';
    return 'medium';
  }

  String _getLanguageDisplayName(String code) {
    switch (code) {
      case 'es':
        return 'Español';
      case 'en':
        return 'English';
      default:
        return AppLocalizations.of(context)!.systemLanguage;
    }
  }
}
