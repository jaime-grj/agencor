import 'package:agencor/models/event_page.dart';
import 'package:agencor/network_tools/api.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:share_plus/share_plus.dart';
import 'package:flutter_gen/gen_l10n/app_localizations.dart';
import 'package:shared_preferences/shared_preferences.dart';

class EventDetailsScreen extends StatelessWidget {
  EventDetailsScreen({super.key, required this.event});
  final NumberFormat priceFormat = NumberFormat.currency(
    locale: 'es',
    symbol: '',
    decimalDigits: 2,
  );
  final Event event;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(event.title),
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: ListView(
          children: [
            Semantics(
              label: AppLocalizations.of(context)!.eventTitle,
              child: Text(
                event.title,
                style: Theme.of(context).textTheme.headlineMedium,
              ),
            ),
            if (event.mediaFilename != null)
              ClipRRect(
                borderRadius: BorderRadius.circular(10),
                child: 
                Semantics(
                  label: event.mediaAlt,
                  child:
                  Image.network(
                    ApiService().getStorageUrl() + event.mediaFilename,
                    fit: BoxFit.cover,
                    width: double.infinity,
                    errorBuilder: (context, error, stackTrace) {
                      return Image.asset('assets/images/fallback_image.png');
                    },
                  ),
                ),
              ),
            const SizedBox(height: 24),

            Semantics(
              label: AppLocalizations.of(context)!.eventDescription, child: Text(
                event.longDescription,
                style: Theme.of(context).textTheme.bodyLarge,
              ),
            ),
            const SizedBox(height: 24),

            _buildDetailCard(context, event),

            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 8.0),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  TextButton.icon(
                    icon: const Icon(Icons.share),
                    label: Text(
                      AppLocalizations.of(context)!.share,
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(color: Colors.blue, fontWeight: FontWeight.bold),
                    ),
                    onPressed: () => _shareEvent(event),
                  )
                ],
              ),
            ),

            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }
  void _shareEvent(Event event) {
    final text = '${event.title}\n${event.webUrl ?? ''}';
    Share.share(text);
  }
}

Widget _buildDetailCard(BuildContext context, Event event) {
  final locale = Localizations.localeOf(context).languageCode;
  final dateFormatter = DateFormat.yMMMMEEEEd(locale);
  final timeFormatter = DateFormat.Hm(locale);
  DateTime? startDate;
  DateTime? endDate;

  bool isFinalized = false;

  if (event.startDate != null) {
    startDate = DateTime.parse(event.startDate);
    endDate = event.endDate != null ? DateTime.parse(event.endDate!) : startDate;
    isFinalized = DateTime.now().isAfter(endDate);
  }

  return Padding(
    padding: const EdgeInsets.only(bottom: 16),
    child: Column(
      crossAxisAlignment: CrossAxisAlignment.stretch,
      children: [
        if (isFinalized)
          Container(
            padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 12),
            decoration: BoxDecoration(
              color: const Color(0xFFAA0000),
              borderRadius: BorderRadius.circular(10),
            ),
            child: Center(
              child: Text(
                AppLocalizations.of(context)!.eventEnded,
                style: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [

              if (startDate != null && endDate != null) ...[
                ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: Semantics(
                    label: AppLocalizations.of(context)!.eventDate,
                    child: const Icon(Icons.calendar_today),
                  ),
                  title: Text(
                    isSameDay(startDate, endDate)
                        ? dateFormatter.format(startDate)
                        : '${dateFormatter.format(startDate)} ${AppLocalizations.of(context)!.to} ${dateFormatter.format(endDate)}',
                    style: const TextStyle(fontSize: 16),
                  ),
                ),
                if (event.isTimeSet == 1 && isSameDay(startDate, endDate))
                  ListTile(
                    contentPadding: EdgeInsets.zero,
                    leading: 
                    Semantics(
                      label: AppLocalizations.of(context)!.eventTime,
                      child: const Icon(Icons.access_time),
                    ),
                    title: 
                    (startDate == endDate) ?
                      Text(
                        timeFormatter.format(startDate),
                        style: const TextStyle(fontSize: 16),
                      )
                    :
                    Text(
                      '${timeFormatter.format(startDate)} ${AppLocalizations.of(context)!.to} ${timeFormatter.format(endDate)}',
                      style: const TextStyle(fontSize: 16),
                    ),
                  ),
              ],

              if (event.capacity != null)
                ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: Semantics(
                    label: AppLocalizations.of(context)!.eventCapacity,
                    child: const Icon(Icons.people),
                  ),
                  title: Text(
                    '${AppLocalizations.of(context)!.eventCapacity}: ${event.capacity} personas',
                    style: const TextStyle(fontSize: 16),
                  ),
                ),

              if (event.location != null)
                ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: Semantics(
                    label: AppLocalizations.of(context)!.location,
                    child: const Icon(Icons.location_on),
                  ),
                  title: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        '${event.location}',
                        style: const TextStyle(fontSize: 16),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        AppLocalizations.of(context)!.eventOpenMap,
                        style: const TextStyle(
                          color: Colors.blue,
                        ),
                      ),
                    ],
                  ),
                  onTap: () => openMapWithAddress(event.location!),
                ),

              if (event.minPrice != null)
                ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: Semantics(
                    label: AppLocalizations.of(context)!.eventPrice,
                    child: const Icon(Icons.monetization_on),
                  ),
                  title: Text(
                    event.maxPrice == null || event.maxPrice == event.minPrice
                        ? (event.minPrice != null && event.minPrice! > 0
                            ? '${formatPrice(event.minPrice)} €'
                            : AppLocalizations.of(context)!.eventFree)
                        : '${formatPrice(event.minPrice)} ${AppLocalizations.of(context)!.to} ${formatPrice(event.maxPrice)} €',
                    style: const TextStyle(fontSize: 16),
                  ),
                ),

              if (event.url != null)
                Semantics(
                  hint: AppLocalizations.of(context)!.eventMoreInfoHint,
                  child: ListTile(
                    contentPadding: EdgeInsets.zero,
                    leading: const Icon(Icons.link),
                    title: Text(
                      AppLocalizations.of(context)!.eventMoreInfo,
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(fontSize: 16),
                      ),
                    onTap: () async {
                      final prefs = await SharedPreferences.getInstance();
                      final showWarning = prefs.getBool('showWarningWhenOpeningLinks') ?? false;
                      final uri = Uri.parse(event.url!);

                      if (showWarning) {
                        final proceed = await showDialog<bool>(
                          context: context,
                          builder: (context) => AlertDialog(
                            title: Text(AppLocalizations.of(context)!.openUrlWarning),
                            content: Text(AppLocalizations.of(context)!.urlWarningMessage),
                            actions: [
                              TextButton(
                                child: Text(AppLocalizations.of(context)!.cancel),
                                onPressed: () => Navigator.of(context).pop(false),
                              ),
                              TextButton(
                                child: Text(AppLocalizations.of(context)!.continueMessage),
                                onPressed: () => Navigator.of(context).pop(true),
                              ),
                            ],
                          ),
                        );

                        if (proceed != true) return;
                      }

                      if (await canLaunchUrl(uri)) {
                        await launchUrl(uri);
                      } else {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(
                              '${AppLocalizations.of(context)!.errorLoadingURL} ${event.url}',
                              overflow: TextOverflow.ellipsis,
                              maxLines: 2,
                            ),
                          ),
                        );
                      }
                    },
                  ),
                ),
            ],
          ),
      ],
    ),
  );
}

bool isSameDay(DateTime a, DateTime b) =>
    a.year == b.year && a.month == b.month && a.day == b.day;

Future<void> openMapWithAddress(String address) async {
  final Uri googleUrl = Uri.parse('https://www.google.com/maps/search/?api=1&query=${Uri.encodeComponent(address)}');
  
  if (await canLaunchUrl(googleUrl)) {
    await launchUrl(googleUrl);
  } else {
    throw 'Could not open Google Maps';
  }
}

String formatPrice(double? price) {
  if (price == null) return '';
  final NumberFormat priceFormat = NumberFormat.currency(
    locale: 'es',
    symbol: '',
    decimalDigits: 2,
  );
  String formatted = priceFormat.format(price).trim();
  if (price == price.truncateToDouble()) {
    formatted = formatted.replaceAll(',00', '');
  }
  return formatted;
}