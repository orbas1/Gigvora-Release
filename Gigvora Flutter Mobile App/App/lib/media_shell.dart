import 'package:flutter/material.dart';

@immutable
class GigvoraMediaSwipeItem {
  final String title;
  final String? subtitle;
  final String? badge;
  final String? imageUrl;
  final VoidCallback onTap;

  const GigvoraMediaSwipeItem({
    required this.title,
    required this.onTap,
    this.subtitle,
    this.badge,
    this.imageUrl,
  });
}

@immutable
class GigvoraMediaSwipeSection {
  final IconData icon;
  final String label;
  final List<GigvoraMediaSwipeItem> items;

  const GigvoraMediaSwipeSection({
    required this.icon,
    required this.label,
    required this.items,
  });
}

/// Carousel-heavy shell for the profile media hub. Each section is swipeable,
/// mirroring the Instagram/TikTok-style reels and long-form rails on web.
class GigvoraMediaSwipeShell extends StatelessWidget {
  final List<GigvoraMediaSwipeSection> sections;
  final Widget? header;

  const GigvoraMediaSwipeShell({
    super.key,
    required this.sections,
    this.header,
  });

  @override
  Widget build(BuildContext context) {
    return ListView.separated(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      itemBuilder: (context, index) {
        if (index == 0 && header != null) {
            return header!;
        }
        final sectionIndex = header == null ? index : index - 1;
        final section = sections[sectionIndex];
        return _GigvoraMediaSection(section: section);
      },
      separatorBuilder: (_, __) => const SizedBox(height: 24),
      itemCount: header == null ? sections.length : sections.length + 1,
    );
  }
}

class _GigvoraMediaSection extends StatelessWidget {
  const _GigvoraMediaSection({required this.section});

  final GigvoraMediaSwipeSection section;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(section.icon, color: theme.colorScheme.primary),
            const SizedBox(width: 8),
            Text(section.label, style: theme.textTheme.titleMedium),
          ],
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 260,
          child: PageView.builder(
            controller: PageController(viewportFraction: 0.7),
            itemCount: section.items.length,
            itemBuilder: (context, index) {
              final item = section.items[index];
              return Padding(
                padding: const EdgeInsets.only(right: 12),
                child: GestureDetector(
                  onTap: item.onTap,
                  child: _GigvoraMediaCard(item: item),
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}

class _GigvoraMediaCard extends StatelessWidget {
  const _GigvoraMediaCard({required this.item});

  final GigvoraMediaSwipeItem item;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        border: Border.all(color: theme.dividerColor),
        image: item.imageUrl != null
            ? DecorationImage(
                image: NetworkImage(item.imageUrl!),
                fit: BoxFit.cover,
                colorFilter: ColorFilter.mode(
                  Colors.black.withValues(alpha: 0.2),
                  BlendMode.darken,
                ),
              )
            : null,
        color: theme.colorScheme.surfaceContainerHighest,
      ),
      child: Stack(
        children: [
          Positioned(
            left: 16,
            right: 16,
            bottom: 16,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (item.badge != null)
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.black.withValues(alpha: 0.6),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      item.badge!,
                      style: theme.textTheme.labelSmall?.copyWith(color: Colors.white),
                    ),
                  ),
                Text(
                  item.title,
                  style: theme.textTheme.titleMedium?.copyWith(color: Colors.white),
                ),
                if (item.subtitle != null)
                  Text(
                    item.subtitle!,
                    style: theme.textTheme.bodySmall?.copyWith(color: Colors.white70),
                  ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

