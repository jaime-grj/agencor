import 'package:agencor/event_details_screen.dart';
import 'package:agencor/models/category.dart';
import 'package:agencor/models/event_page.dart';
import 'package:agencor/network_tools/api.dart';
import 'package:flutter/material.dart';
import 'package:flutter_gen/gen_l10n/app_localizations.dart';
import 'dart:async';

class SearchPage extends StatefulWidget {
  const SearchPage({super.key});

  @override
  State<SearchPage> createState() => _SearchPageState();
}

class _SearchPageState extends State<SearchPage> {
  Timer? _debounce;
  final scrollController = ScrollController();
  ApiService apiService = ApiService();
  List<Event> events = [];
  List<Category> categories = [];
  int page = 1;
  bool isLoading = false;
  bool networkConnection = true;
  bool _showAdvancedFilters = false;
  String _searchQueryTitle = '';
  DateTime? _selectedBeforeDate;
  DateTime? _selectedAfterDate;
  String? _selectedLocation;
  Map<String, String> _selectedCategories = {};
  double? _minPrice;
  double? _maxPrice;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Semantics(
          label: AppLocalizations.of(context)!.searchField,
          hint: AppLocalizations.of(context)!.searchFieldHint,
          textField: true,
          child: TextField(
            decoration: InputDecoration(
              hintText: AppLocalizations.of(context)!.search,
              hintStyle: TextStyle(
                color: Theme.of(context).brightness == Brightness.dark
                    ? const Color(0xFFF0F0F0)
                    : const Color(0xFF0F0F0F),
              ),
              suffixIcon: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Tooltip(
                    message: _showAdvancedFilters
                        ? AppLocalizations.of(context)!.hideAdvancedFilters
                        : AppLocalizations.of(context)!.showAdvancedFilters,
                    child: IconButton(
                      onPressed: () {
                        setState(() {
                          _showAdvancedFilters = !_showAdvancedFilters;
                        });
                      },
                      icon: Icon(
                        Icons.filter_list,
                        color: Theme.of(context).iconTheme.color,
                      ),
                      splashRadius: 20,
                      padding: const EdgeInsets.all(0),
                      constraints: const BoxConstraints(),
                      tooltip: _showAdvancedFilters
                          ? AppLocalizations.of(context)!.hideAdvancedFilters
                          : AppLocalizations.of(context)!.showAdvancedFilters,
                    ),
                  ),
                ],
              ),
            ),
            onChanged: (query) {
              setState(() {
                isLoading = true;
                _searchQueryTitle = query;
                page = 1;
              });
              if (_debounce?.isActive ?? false) _debounce!.cancel();
              _debounce = Timer(const Duration(milliseconds: 250), () {
                _fetchSearchResults();
              });
            },
          ),
        ),
      ),
      body: Column(
        children: [
          AnimatedSize(
            duration: const Duration(milliseconds: 300),
            curve: Curves.easeInOut,
            child: _showAdvancedFilters
                ? _buildAdvancedFilters(context)
                : Container(),
          ),
          Expanded(
            child: networkConnection
                ? ListView.separated(
                    padding: const EdgeInsets.all(5.0),
                    controller: scrollController,
                    itemCount: isLoading ? events.length + 1 : events.length,
                    itemBuilder: (BuildContext context, int index) {
                      if (index < events.length) {
                        return _buildEventCard(context, events[index]);
                      } else {
                        return const Center(child: CircularProgressIndicator());
                      }
                    },
                    separatorBuilder: (context, index) => const Divider(),
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
                      ],
                    ),
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildEventCard(BuildContext context, Event event) {
    final now = DateTime.now();
    bool isFinalized = false;

    if (event.startDate != null) {
      final DateTime startDate = DateTime.parse(event.startDate);
      final DateTime endDate = event.endDate != null
          ? DateTime.parse(event.endDate!)
          : startDate;

      isFinalized = now.isAfter(endDate);
    }

    return GestureDetector(
        onTap: () => Navigator.push(
            context,
            MaterialPageRoute(
                builder: (context) => EventDetailsScreen(event: event))),
      child: Card(
        shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(10)),
        ),
        elevation: 0,
        clipBehavior: Clip.antiAlias,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: <Widget>[
            if (isFinalized)
              Container(
                padding: const EdgeInsets.symmetric(vertical: 4.0, horizontal: 8.0),
                color: const Color(0xFFAA0000),
                child: Text(
                  AppLocalizations.of(context)!.eventEnded,
                  style: const TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
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
                constraints: const BoxConstraints(maxWidth: 250),
                child: Semantics(
                  label: event.mediaAlt,
                  child:
                  Image.network(
                    apiService.getStorageUrl() + event.mediaFilename,
                    fit: BoxFit.fill,
                  ),
                ),
              ),
          ],
        ),
      )
    );
  }


  Future<void> _fetchSearchResults() async {
    try {
      if (!mounted) return;
      if (_searchQueryTitle.isEmpty &&
          _selectedCategories.isEmpty == true &&
          (_selectedLocation == null || _selectedLocation?.isEmpty == true) &&
          (_selectedBeforeDate == null && _selectedAfterDate == null) &&
          (_minPrice == null && _maxPrice == null)) {
        setState(() {
          isLoading = false;
        });
        return;
      }
      if (page == 1) {
        if (mounted) {
          setState(() {
            events = [];
          });
        }
      }
      if (mounted) {
        setState(() {
          isLoading = true;
          networkConnection = true;
        });
      }

      List<Event> eventsList = await apiService.getSearchResults(
          page,
          _searchQueryTitle,
          _selectedLocation,
          _selectedCategories,
          _selectedBeforeDate,
          _selectedAfterDate,
          _minPrice,
          _maxPrice,);

      if (mounted) {
        setState(() {
          events = events + eventsList;
          isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          networkConnection = false;
          isLoading = false;
        });
      }
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

  @override
  void initState() {
    super.initState();
    scrollController.addListener(_scrollListener);
    _fetchCategories();
  }

  void _scrollListener() async {
    if (isLoading) {
      return;
    }
    if (scrollController.position.pixels ==
        scrollController.position.maxScrollExtent) {
      setState(() {
        isLoading = true;
      });
      page++;
      await _fetchSearchResults();
      setState(() {
        isLoading = false;
      });
    } else {}
  }

  Widget _buildAdvancedFilters(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: Column(
        children: [
          _buildLocationField(context),
          const SizedBox(height: 10),
          _buildCategoryField(context),
          const SizedBox(height: 10),
          _buildBeforeDateField(context),
          const SizedBox(height: 10),
          _buildAfterDateField(context),
          const SizedBox(height: 10),
          _buildPriceRangeField(context),
          const SizedBox(height: 10),
        ],
      ),
    );
  }

  void _showMultiSelectCategoryDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return MultiSelectDialog(
          title: AppLocalizations.of(context)!.selectCategories,
          options: categories.asMap().map((index, category) {
            return MapEntry(category.id.toString(), category.name);
          }),
          selectedValues: _selectedCategories,
          onConfirm: (selectedValues) {
            setState(() {
              _selectedCategories = selectedValues;
              isLoading = true;
              page = 1;
              _fetchSearchResults();
            });
          },
        );
      },
    );
  }

  Widget _buildLocationField(BuildContext context) {
    return Semantics(
      label: AppLocalizations.of(context)!.locationField,
      hint: AppLocalizations.of(context)!.locationFieldHint,
      textField: true,
      child: TextFormField(
        decoration: InputDecoration(
          labelText: AppLocalizations.of(context)!.location,
          labelStyle: TextStyle(
            color: Theme.of(context).brightness == Brightness.dark
                ? const Color(0xFFF0F0F0)
                : const Color(0xFF0F0F0F),
          ),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
          suffixIcon: _selectedLocation != null && _selectedLocation!.isNotEmpty
              ? IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    setState(() {
                      _selectedLocation = '';
                    });
                  },
                  tooltip: 'Clear location input',
                )
              : null,
        ),
        onChanged: (value) {
          setState(() {
            isLoading = true;
            _selectedLocation = value;
            page = 1;
            _fetchSearchResults();
          });
        },
      ),
    );
  }

  Widget _buildCategoryField(BuildContext context) {
    return Semantics(
      label: AppLocalizations.of(context)!.categoriesField,
      hint: AppLocalizations.of(context)!.categoriesFieldHint,
      textField: true,
      readOnly: true,
      child: TextFormField(
        readOnly: true,
        decoration: InputDecoration(
          labelText: AppLocalizations.of(context)!.categories,
          labelStyle: TextStyle(
            color: Theme.of(context).brightness == Brightness.dark
                ? const Color(0xFFF0F0F0)
                : const Color(0xFF0F0F0F),
          ),
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
          suffixIcon: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              if (_selectedCategories.isNotEmpty)
                Semantics(
                  label: AppLocalizations.of(context)!.clearFilterCategories,
                  button: true,
                  child: IconButton(
                    icon: const Icon(Icons.clear),
                    onPressed: () {
                      setState(() {
                        _selectedCategories.clear();
                      });
                    },
                  ),
                ),
              Semantics(
                label: AppLocalizations.of(context)!.selectCategories,
                button: true,
                child: IconButton(
                  icon: const Icon(Icons.arrow_drop_down),
                  onPressed: () => _showMultiSelectCategoryDialog(context),
                ),
              ),
            ],
          ),
        ),
        controller: TextEditingController(
          text: _selectedCategories.isNotEmpty
              ? _selectedCategories.values.join(', ')
              : '',
        ),
        onTap: () => _showMultiSelectCategoryDialog(context),
      ),
    );
  }

  Widget _buildBeforeDateField(BuildContext context) {
    return TextFormField(
      readOnly: true,
      decoration: InputDecoration(
        labelText: AppLocalizations.of(context)!.beforeDate,
        labelStyle: TextStyle(
          color: Theme.of(context).brightness == Brightness.dark
              ? const Color(0xFFF0F0F0)
              : const Color(0xFF0F0F0F),
        ),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
        suffixIcon: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (_selectedBeforeDate != null)
              Semantics(
                label: AppLocalizations.of(context)!.clearFilterBeforeDate,
                button: true,
                child: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    setState(() {
                      _selectedBeforeDate = null;
                    });
                  },
                ),
              ),   
            Semantics(
              label: AppLocalizations.of(context)!.selectDate,
              button: true,
              child: IconButton(
                icon: const Icon(Icons.calendar_today),
                onPressed: () async {
                  final pickedDate = await showDatePicker(
                    context: context,
                    locale: const Locale("es", "ES"),
                    initialDate: _selectedBeforeDate ?? DateTime.now(),
                    firstDate: DateTime(2000),
                    lastDate: DateTime(2101),
                    cancelText: AppLocalizations.of(context)!.cancel,
                    confirmText: AppLocalizations.of(context)!.confirm,
                  );
                  if (pickedDate != null) {
                    setState(() {
                      _selectedBeforeDate = pickedDate;
                      isLoading = true;
                      page = 1;
                      _fetchSearchResults();
                    });
                  }
                },
              ),
            ),
          ],
        ),
      ),
      onTap: () async {
        final pickedDate = await showDatePicker(
          context: context,
          locale: const Locale("es", "ES"),
          initialDate: _selectedBeforeDate ?? DateTime.now(),
          firstDate: DateTime(2000),
          lastDate: DateTime(2101),
          cancelText: AppLocalizations.of(context)!.cancel,
          confirmText: AppLocalizations.of(context)!.confirm,
        );
        if (pickedDate != null) {
          setState(() {
            _selectedBeforeDate = pickedDate;
            isLoading = true;
            page = 1;
            _fetchSearchResults();
          });
        }
      },
      controller: TextEditingController(
        text: _selectedBeforeDate != null
            ? "${_selectedBeforeDate!.day}/${_selectedBeforeDate!.month}/${_selectedBeforeDate!.year}"
            : '',
      ),
    );
  }

  Widget _buildAfterDateField(BuildContext context) {
    return TextFormField(
      readOnly: true,
      decoration: InputDecoration(
        labelText: AppLocalizations.of(context)!.afterDate,
        labelStyle: TextStyle(
          color: Theme.of(context).brightness == Brightness.dark
              ? const Color(0xFFF0F0F0)
              : const Color(0xFF0F0F0F),
        ),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
        suffixIcon: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            if (_selectedAfterDate != null)
              Semantics(
                label: AppLocalizations.of(context)!.clearFilterAfterDate,
                child: IconButton(
                  icon: const Icon(Icons.clear),
                  onPressed: () {
                    setState(() {
                      _selectedAfterDate = null;
                    });
                  },
                ),
              ),
            Semantics(
              label: AppLocalizations.of(context)!.selectDate,
              child: IconButton(
                icon: const Icon(Icons.calendar_today),
                onPressed: () async {
                  final pickedDate = await showDatePicker(
                    context: context,
                    locale: const Locale("es", "ES"),
                    initialDate: _selectedAfterDate ?? DateTime.now(),
                    firstDate: DateTime(2000),
                    lastDate: DateTime(2101),
                    cancelText: AppLocalizations.of(context)!.cancel,
                    confirmText: AppLocalizations.of(context)!.confirm,
                  );
                  if (pickedDate != null) {
                    setState(() {
                      _selectedAfterDate = pickedDate;
                    });
                  }
                },
              ),
            ),
          ],
        ),
      ),
      onTap: () async {
        final pickedDate = await showDatePicker(
          context: context,
          locale: const Locale("es", "ES"),
          initialDate: _selectedAfterDate ?? DateTime.now(),
          firstDate: DateTime(2000),
          lastDate: DateTime(2101),
        );
        if (pickedDate != null) {
          setState(() {
            _selectedAfterDate = pickedDate;
            _fetchSearchResults();
          });
        }
      },
      controller: TextEditingController(
        text: _selectedAfterDate != null
            ? "${_selectedAfterDate!.day}/${_selectedAfterDate!.month}/${_selectedAfterDate!.year}"
            : '',
      ),
    );
  }

  Widget _buildPriceRangeField(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: Semantics(
            label: AppLocalizations.of(context)!.minPriceInEuros,
            hint: AppLocalizations.of(context)!.minPriceInEurosHint,
            textField: true,
            child: TextFormField(
              keyboardType: TextInputType.number,
              decoration: InputDecoration(
                labelText: AppLocalizations.of(context)!.minPrice,
                labelStyle: TextStyle(
                  color: Theme.of(context).brightness == Brightness.dark
                      ? const Color(0xFFF0F0F0)
                      : const Color(0xFF0F0F0F),
                ),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
              onChanged: (value) {
                setState(() {
                  isLoading = true;
                  _minPrice = double.tryParse(value);
                  page = 1;
                  _fetchSearchResults();
                });
              },
            ),
          )
        ),
        const SizedBox(width: 10),
        Expanded(
          child: Semantics(
            label: AppLocalizations.of(context)!.maxPriceInEuros,
            hint: AppLocalizations.of(context)!.maxPriceInEurosHint,
            textField: true,
            child: TextFormField(
              keyboardType: TextInputType.number,
              decoration: InputDecoration(
                labelText: AppLocalizations.of(context)!.maxPrice,
                labelStyle: TextStyle(
                  color: Theme.of(context).brightness == Brightness.dark
                      ? const Color(0xFFF0F0F0)
                      : const Color(0xFF0F0F0F),
                ),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
              ),
              onChanged: (value) {
                setState(() {
                  isLoading = true;
                  _maxPrice = double.tryParse(value);
                  page = 1;
                  _fetchSearchResults();
                });
              },
            ),
          )
        ),
      ],
    );
  }

  @override
  void dispose() {
    _debounce?.cancel();
    scrollController.dispose();
    super.dispose();
  }
}

class Debouncer {
  final int milliseconds;
  Timer? _timer;
  Debouncer({required this.milliseconds});
  void run(VoidCallback action) {
    if (_timer != null) {
      _timer!.cancel();
    }
    _timer = Timer(Duration(milliseconds: milliseconds), action);
  }
}

class MultiSelectDialog extends StatefulWidget {
  final String title;
  final Map<String, String> options;
  final Map<String, String> selectedValues;
  final void Function(Map<String, String>) onConfirm;

  const MultiSelectDialog({
    Key? key,
    required this.title,
    required this.options,
    required this.selectedValues,
    required this.onConfirm,
  }) : super(key: key);

  @override
  State<MultiSelectDialog> createState() => _MultiSelectDialogState();
}

class _MultiSelectDialogState extends State<MultiSelectDialog> {
  late Map<String, String> _tempSelectedValues;

  @override
  void initState() {
    super.initState();
    _tempSelectedValues = Map.from(widget.selectedValues);
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: Text(widget.title),
      content: SingleChildScrollView(
        child: ListBody(
          children: widget.options.entries.map((entry) {
            return CheckboxListTile(
              value: _tempSelectedValues.containsKey(entry.key),
              title: Text(entry.value),
              controlAffinity: ListTileControlAffinity.leading,
              onChanged: (isChecked) {
                setState(() {
                  if (isChecked!) {
                    _tempSelectedValues[entry.key] = entry.value;
                  } else {
                    _tempSelectedValues.remove(entry.key);
                  }
                });
              },
            );
          }).toList(),
        ),
      ),
      actions: <Widget>[
        TextButton(
          child: Text(AppLocalizations.of(context)!.cancel),
          onPressed: () {
            Navigator.of(context).pop();
          },
        ),
        TextButton(
          child: Text(AppLocalizations.of(context)!.confirm),
          onPressed: () {
            widget.onConfirm(_tempSelectedValues);
            Navigator.of(context).pop();
          },
        ),
      ],
    );
  }
}
