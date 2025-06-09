import 'package:agencor/config_page.dart';
import 'package:agencor/event_details_screen.dart';
import 'package:agencor/models/event_page.dart';
import 'package:agencor/network_tools/api.dart';
import 'package:flutter/material.dart';
import 'package:agencor/search_page.dart';
import 'package:group_button/group_button.dart';
import 'package:agencor/models/category.dart';
import 'package:intl/intl.dart';
import 'package:flutter_gen/gen_l10n/app_localizations.dart';
import 'dart:async';

class HomePage extends StatefulWidget {
  const HomePage({super.key, required this.title});

  final String title;

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  ApiService apiService = ApiService();
  int page = 1;
  final scrollController = ScrollController();
  List<Event> events = [];
  List<Event> featuredEvents = [];
  List<Category> categories = [];
  bool isLoading = false;
  final controller = GroupButtonController();
  bool networkConnection = true;
  int loadedCategory = 0;

  @override
  Widget build(BuildContext context) {
    return PopScope(
    canPop: loadedCategory == 0,
    onPopInvoked: (didPop) {
      if (!didPop && loadedCategory != 0) {
        setState(() {
          loadedCategory = 0;
          events = [];
          page = 1;
          isLoading = true;
        });
        _fetchEvents(page);
        _fetchFeaturedEvents();
      }
    },
    child: Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context)!.appName),
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        actions: [
          Semantics(
            label: AppLocalizations.of(context)!.search,
            child: IconButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => const SearchPage(),
                  ),
                );
              },
              icon: const Icon(Icons.search),
            ),
          ),
          
        ],
      ),
      drawer: Drawer(
        child: ListView(
          padding: EdgeInsets.zero,
          children: <Widget>[
            DrawerHeader(
              decoration: const BoxDecoration(
                color: Colors.blueAccent,
              ),
              child: Semantics(
                label: AppLocalizations.of(context)!.appIcon,
                child: Image.asset(
                  'assets/images/icon_transparent.png',
                  fit: BoxFit.contain,
                ),
              ),
            ),
            ListTile(
              leading: const Icon(Icons.home),
              title: Text(AppLocalizations.of(context)!.homeMenu),
              onTap: () {
                events = [];
                page = 1;
                loadedCategory = 0;
                isLoading = true;
                _fetchEvents(page);
                Navigator.of(context).pop();
              },
            ),
            ListTile(
              leading: const Icon(Icons.settings),
              title: Text(AppLocalizations.of(context)!.settingsMenu),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => const SettingsPage(),
                  ),
                );
              },
            ),
            const Divider(),
            ListTile(
              title: Text(AppLocalizations.of(context)!.categories),
            ),
            ...categories.map((category) {
              return ListTile(
                leading: const Icon(Icons.list_alt),
                title: Text(category.name),
                onTap: () {
                  setState(() {
                    events = [];
                    page = 1;
                    isLoading = true;
                    loadedCategory = category.id;
                  });
                  _fetchEventsByCategory(category.id);
                  Navigator.of(context).pop();
                }
              );
            }).toList(),
          ],
        ),
      ),
      body: networkConnection == true
    ? RefreshIndicator(
        onRefresh: _handleRefresh,
        child: CustomScrollView(
          controller: scrollController,
          slivers: [
            SliverToBoxAdapter(
              child: loadedCategory != 0
                  ? const SizedBox()
                  : Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const SizedBox(height: 10),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          child: Center(
                            child: Text(
                              AppLocalizations.of(context)!.featuredEvents,
                              style: Theme.of(context).textTheme.headlineSmall,
                            ),
                          ),
                        ),
                        const SizedBox(height: 10),
                        SizedBox(
                          height: 150,
                          child: ListView.builder(
                            padding:
                                const EdgeInsets.symmetric(horizontal: 10.0),
                            scrollDirection: Axis.horizontal,
                            itemCount: featuredEvents.length,
                            itemBuilder: (context, index) {
                              return _buildfeaturedEventCard(
                                  context, featuredEvents[index]);
                            },
                          ),
                        ),
                      ],
                    ),
            ),
            const SliverToBoxAdapter(
              child: SizedBox(height: 20),
            ),
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Center(
                  child: Text(
                    loadedCategory == 0
                        ? AppLocalizations.of(context)!.upcomingEvents
                        : categories
                            .firstWhere(
                                (category) => category.id == loadedCategory)
                            .name,
                    style: Theme.of(context).textTheme.headlineSmall,
                  ),
                ),
              ),
            ),
            events.isEmpty && !isLoading
                ? SliverFillRemaining(
                    hasScrollBody: false,
                    child: Container(
                      color: Theme.of(context).scaffoldBackgroundColor,
                      child: Center(
                        child: Text(
                          loadedCategory == 0
                              ? AppLocalizations.of(context)!.noEvents
                              : AppLocalizations.of(context)!.noEventsInCategory,
                          style: const TextStyle(fontSize: 20),
                        ),
                      ),
                    ),
                  )
                : SliverList(
                    delegate: SliverChildBuilderDelegate(
                      (context, index) {
                        if (index < events.length) {
                          return _buildEventCard(context, events[index]);
                        } else {
                          return const Padding(
                            padding: EdgeInsets.symmetric(vertical: 20),
                            child: Center(
                              child: CircularProgressIndicator(),
                            ),
                          );
                        }
                      },
                      childCount: isLoading
                          ? events.length + 1
                          : events.length,
                    ),
                  ),
          ],
        ),
      )
          : Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Icon(
                    Icons.wifi_off,
                    color: Theme.of(context).colorScheme.primary,
                  ),
                  Text(
                    AppLocalizations.of(context)!.noInternet,
                    textAlign: TextAlign.center,
                  ),
                  ButtonTheme(
                    child: ElevatedButton(
                      style: ButtonStyle(
                        shape: WidgetStatePropertyAll(
                          RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(10),
                          ),
                        ),
                      ),
                      onPressed: () {
                        setState(() {
                          page = 1;
                          events = [];
                          isLoading = true;
                          networkConnection = true;
                          _fetchCategories();
                          if (loadedCategory == 0) {
                            _fetchEvents(page);
                            _fetchFeaturedEvents();
                          } else {
                            _fetchEventsByCategory(loadedCategory);
                          }
                        });
                      },
                      child: Text(AppLocalizations.of(context)!.retry),
                    ),
                  ),
                ],
              ),
            ),
      ),
    );
  }

  Widget _buildfeaturedEventCard(BuildContext context, Event event) {
    return SizedBox(
      width: 250,
      child: Semantics(
        label: event.title,
        hint: AppLocalizations.of(context)!.eventCardHint,
        button: true,
        child: GestureDetector(
          onTap: () {
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => EventDetailsScreen(event: event),
              ),
            );
          },
          child: Card(
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
            elevation: 0,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: <Widget>[
                if (event.mediaFilename != null)
                  ConstrainedBox(
                    constraints:
                        const BoxConstraints(maxWidth: 250, maxHeight: 142),
                    child: ClipRRect(
                      borderRadius: BorderRadius.circular(10),
                      child: Semantics(
                        label: event.mediaAlt,
                        image: true,
                        child: Image.network(
                          apiService.getStorageUrl() + event.mediaFilename,
                          fit: BoxFit.fill,
                        ),
                      ),
                    ),
                  ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildEventCard(BuildContext context, Event event) {
  final locale = Localizations.localeOf(context).languageCode;
  final DateFormat dateTimeFormat = DateFormat('yMMMMEEEEd', locale);

  String buildBottomText() {
    if (event.endDate != null) {
      return '${AppLocalizations.of(context)!.dateFrom} ${dateTimeFormat.format(DateTime.parse(event.startDate))}';
    } else if (event.startDate != null) {
      return dateTimeFormat.format(DateTime.parse(event.startDate));
    } else if (event.url != null) {
      return event.url!;
    } else {
      return '';
    }
  }

  return GestureDetector(
    onTap: () => Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => EventDetailsScreen(event: event),
      ),
    ),
    child: Semantics(
      hint: AppLocalizations.of(context)!.eventCardHint,
      button: true,
      child: Card(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        elevation: 0,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            ListTile(
              title: Text(
                event.title,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                ),
              ),
              subtitle: event.shortDescription != null
                  ? Text(event.shortDescription ?? '')
                  : const SizedBox(),
            ),
            if (event.mediaFilename != null)
              ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 320),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(10),
                  child: Semantics(
                    label: event.mediaAlt,
                    child:
                    Image.network(
                      apiService.getStorageUrl() + event.mediaFilename,
                      fit: BoxFit.fill,
                    ),
                  ),
                ),
              ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8.0),
              child: Text(
                buildBottomText(),
                overflow: TextOverflow.ellipsis,
                maxLines: 2,
                textAlign: TextAlign.center,
              ),
            ),
            const Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: <Widget>[
                SizedBox(height: 20),
              ],
            ),
          ],
        ),
      ),
    )
  );
}

  Future<void> _fetchEvents(int page) async {
    try {
      networkConnection = true;
      isLoading = true;
      List<Event> eventsList = await apiService.getEvents(page);
      setState(() {
        events = events + eventsList;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        networkConnection = false;
        isLoading = false;
      });
    }
  }

  Future<void> _fetchEventsByCategory(int id) async {
    setState(() {
      loadedCategory = id;
    });
    try {
      List<Event> eventsList =
          await apiService.getEventsByCategory(loadedCategory, page);
      setState(() {
        events = events + eventsList;
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        networkConnection = false;
        isLoading = false;
      });
    }
  }

  Future<void> _fetchFeaturedEvents() async {
    try {
      List<Event> featuredEventsList =
          await apiService.getFeaturedEvents(page);
      setState(() {
        featuredEvents = featuredEventsList;
      });
    } catch (e) {
      print(e);
      setState(() {
        networkConnection = false;
        isLoading = false;
      });
    }
  }

  Future<void> _fetchCategories() async {
    try {
      List<Category> categoriesList = await apiService.getCategories();
      setState(() {
        categories = categoriesList;
      });
    } catch (e) {
      setState(() {
        networkConnection = false;
        isLoading = false;
      });
    }
  }

  Future<void> _handleRefresh() async {
    setState(() {
      page = 1;
      events = [];
      isLoading = true;
      networkConnection = true;
    });

    if (loadedCategory == 0) {
      await _fetchEvents(page);
      await _fetchFeaturedEvents();
    } else {
      await _fetchEventsByCategory(loadedCategory);
    }
    await _fetchCategories();
  }

  @override
  void initState() {
    super.initState();
    scrollController.addListener(_scrollListener);
    isLoading = true;
    networkConnection = true;
    _fetchEvents(page);
    _fetchCategories();
    _fetchFeaturedEvents();
  }

  void _scrollListener() async {
    if (isLoading || !scrollController.hasClients) return;

    if (scrollController.position.pixels >=
        scrollController.position.maxScrollExtent - 200) {
      setState(() {
        isLoading = true;
        page++;
      });

      if (loadedCategory == 0) {
        await _fetchEvents(page);
      } else {
        await _fetchEventsByCategory(loadedCategory);
      }

      setState(() {
        isLoading = false;
      });
    }
  }
}
