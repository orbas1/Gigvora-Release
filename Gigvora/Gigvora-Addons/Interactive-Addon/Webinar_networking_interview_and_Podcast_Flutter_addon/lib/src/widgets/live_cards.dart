import 'package:flutter/material.dart';

import '../theme/live_mobile_theme.dart';

class LiveEventCard extends StatelessWidget {
  const LiveEventCard({
    super.key,
    required this.title,
    required this.subtitle,
    required this.meta,
    required this.onTap,
    this.trailing,
    this.leading,
  });

  final String title;
  final String subtitle;
  final String meta;
  final VoidCallback onTap;
  final Widget? trailing;
  final Widget? leading;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              leading ?? const Icon(Icons.play_circle_outline, size: 40),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(title, style: Theme.of(context).textTheme.titleMedium),
                    const SizedBox(height: 4),
                    Text(subtitle,
                        style: Theme.of(context)
                            .textTheme
                            .bodyMedium
                            ?.copyWith(color: LiveMobileTheme.mutedText(context))),
                    const SizedBox(height: 8),
                    Text(meta,
                        style: Theme.of(context)
                            .textTheme
                            .bodySmall
                            ?.copyWith(color: LiveMobileTheme.metaText(context))),
                  ],
                ),
              ),
              if (trailing != null) trailing!,
            ],
          ),
        ),
      ),
    );
  }
}

class InfoChip extends StatelessWidget {
  const InfoChip({super.key, required this.label, this.color});
  final String label;
  final Color? color;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: color ?? Theme.of(context).colorScheme.primary.withOpacity(0.12),
        borderRadius: BorderRadius.circular(LiveMobileTheme.cardRadius),
      ),
      child: Text(label, style: Theme.of(context).textTheme.labelMedium),
    );
  }
}
