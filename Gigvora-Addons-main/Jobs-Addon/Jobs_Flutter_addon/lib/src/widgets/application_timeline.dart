import 'package:flutter/material.dart';

class ApplicationTimeline extends StatelessWidget {
  const ApplicationTimeline({super.key, required this.events});

  final List<TimelineEvent> events;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: events
          .map(
            (event) => Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Column(
                  children: [
                    Icon(event.icon, color: Theme.of(context).colorScheme.primary),
                    if (event != events.last)
                      Container(height: 30, width: 2, color: Theme.of(context).dividerColor),
                  ],
                ),
                const SizedBox(width: 8),
                Expanded(
                  child: Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(event.label, style: Theme.of(context).textTheme.titleSmall),
                        Text(event.subtitle, style: Theme.of(context).textTheme.bodySmall),
                      ],
                    ),
                  ),
                )
              ],
            ),
          )
          .toList(),
    );
  }
}

class TimelineEvent {
  const TimelineEvent({required this.label, required this.subtitle, this.icon = Icons.check_circle_outline});

  final String label;
  final String subtitle;
  final IconData icon;
}
