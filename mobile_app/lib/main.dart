import 'package:agencor/home_page.dart';
import 'package:flutter/material.dart';
import 'package:flutter_gen/gen_l10n/app_localizations.dart';
import 'package:provider/provider.dart';
import 'providers/theme_provider.dart';
import 'providers/text_size_provider.dart';
import 'providers/locale_provider.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => ThemeProvider()),
        ChangeNotifierProvider(create: (_) => TextSizeProvider()),
        ChangeNotifierProvider(create: (_) => LocaleProvider()),
      ],
      child: Consumer3<ThemeProvider, TextSizeProvider, LocaleProvider>(
        builder: (context, themeProvider, textSizeProvider, localeProvider, child) {
          return Builder(
            builder: (context) {
              return MaterialApp(
                title: 'Agencor',
                theme: ThemeData(
                  useMaterial3: true,
                  colorScheme: ColorScheme.fromSeed(
                    seedColor: Colors.blue,
                    brightness: Brightness.light,
                  ),
                ),
                darkTheme: (() {
                  final brightness = WidgetsBinding.instance.platformDispatcher.platformBrightness;
                  final shouldUsePureBlack = themeProvider.isPureBlack &&
                      (themeProvider.themeMode == ThemeMode.dark ||
                      (themeProvider.themeMode == ThemeMode.system && brightness == Brightness.dark));

                  return shouldUsePureBlack
                      ? ThemeData(
                          useMaterial3: true,
                          brightness: Brightness.dark,
                          scaffoldBackgroundColor: Colors.black,
                          colorScheme: ColorScheme.fromSeed(
                            seedColor: Colors.blue,
                            brightness: Brightness.dark,
                          ).copyWith(
                            surface: Colors.black,
                          ),
                        )
                      : ThemeData(
                          useMaterial3: true,
                          colorScheme: ColorScheme.fromSeed(
                            seedColor: Colors.blue,
                            brightness: Brightness.dark,
                          ),
                        );
                })(),
                themeMode: themeProvider.themeMode,
                locale: localeProvider.locale,
                supportedLocales: AppLocalizations.supportedLocales,
                localizationsDelegates: AppLocalizations.localizationsDelegates,
                localeResolutionCallback: (locale, supportedLocales) {
                  if (locale == null) return supportedLocales.first;

                  for (var supportedLocale in supportedLocales) {
                    if (supportedLocale.languageCode == locale.languageCode) {
                      return supportedLocale;
                    }
                  }
                  return supportedLocales.first;
                },
                home: const HomePage(title: 'AGENCOR'),
                builder: (context, child) {
                  final mediaQuery = MediaQuery.of(context);
                  return MediaQuery(
                    data: mediaQuery.copyWith(
                      textScaler: TextScaler.linear(textSizeProvider.scaleFactor),
                    ),
                    child: child!,
                  );
                },
              );
            },
          );
        },
      ),
    );
  }
}
