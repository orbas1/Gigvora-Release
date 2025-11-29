import 'package:flutter/material.dart';

import '../../../state/gig_provider.dart';

class GigFilterSheet extends StatefulWidget {
  const GigFilterSheet({super.key, required this.initialFilters});

  final GigFilters initialFilters;

  @override
  State<GigFilterSheet> createState() => _GigFilterSheetState();
}

class _GigFilterSheetState extends State<GigFilterSheet> {
  late TextEditingController keywordController;
  late TextEditingController locationController;
  late TextEditingController minController;
  late TextEditingController maxController;
  String sortBy = '';

  @override
  void initState() {
    super.initState();
    keywordController = TextEditingController(text: widget.initialFilters.keyword);
    locationController = TextEditingController(text: widget.initialFilters.location ?? '');
    minController = TextEditingController(text: widget.initialFilters.minPrice?.toString() ?? '');
    maxController = TextEditingController(text: widget.initialFilters.maxPrice?.toString() ?? '');
    sortBy = widget.initialFilters.sortBy ?? '';
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).viewInsets.bottom,
        left: 16,
        right: 16,
        top: 24,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text('Filter gigs', style: Theme.of(context).textTheme.titleLarge),
          const SizedBox(height: 12),
          TextField(
            controller: keywordController,
            decoration: const InputDecoration(labelText: 'Keyword'),
          ),
          Row(
            children: [
              Expanded(
                child: TextField(
                  controller: minController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Min budget'),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: TextField(
                  controller: maxController,
                  keyboardType: TextInputType.number,
                  decoration: const InputDecoration(labelText: 'Max budget'),
                ),
              ),
            ],
          ),
          TextField(
            controller: locationController,
            decoration: const InputDecoration(labelText: 'Location'),
          ),
          DropdownButtonFormField<String>(
            value: sortBy.isEmpty ? null : sortBy,
            decoration: const InputDecoration(labelText: 'Sort by'),
            items: const [
              DropdownMenuItem(value: 'price_low_high', child: Text('Price (low to high)')),
              DropdownMenuItem(value: 'price_high_low', child: Text('Price (high to low)')),
              DropdownMenuItem(value: 'recent', child: Text('Recently added')),
            ],
            onChanged: (value) => setState(() => sortBy = value ?? ''),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed: () => Navigator.of(context).pop(widget.initialFilters),
                  child: const Text('Reset'),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.of(context).pop(
                      GigFilters(
                        keyword: keywordController.text,
                        location: locationController.text.isEmpty ? null : locationController.text,
                        minPrice: double.tryParse(minController.text),
                        maxPrice: double.tryParse(maxController.text),
                        sortBy: sortBy,
                      ),
                    );
                  },
                  child: const Text('Apply'),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
        ],
      ),
    );
  }
}
