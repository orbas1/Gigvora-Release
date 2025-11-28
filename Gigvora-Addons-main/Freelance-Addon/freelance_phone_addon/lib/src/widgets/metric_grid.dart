import 'package:flutter/material.dart';

class MetricGrid extends StatelessWidget {
  const MetricGrid({super.key, required this.metrics});
  final List<MetricItem> metrics;

  @override
  Widget build(BuildContext context) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisSpacing: 12,
      mainAxisSpacing: 12,
      children: metrics
          .map((m) => Card(
                child: Padding(
                  padding: const EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(m.label, style: Theme.of(context).textTheme.labelMedium),
                      const SizedBox(height: 8),
                      Text(m.value, style: Theme.of(context).textTheme.headlineSmall),
                      if (m.hint != null) Text(m.hint!, style: Theme.of(context).textTheme.bodySmall),
                    ],
                  ),
                ),
              ))
          .toList(),
    );
  }
}

class MetricItem {
  MetricItem({required this.label, required this.value, this.hint});
  final String label;
  final String value;
  final String? hint;
}
