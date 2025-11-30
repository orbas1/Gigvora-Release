import 'package:flutter/material.dart';

@immutable
class GigvoraProfileQuickLink {
  final IconData icon;
  final String label;
  final VoidCallback onTap;

  const GigvoraProfileQuickLink({
    required this.icon,
    required this.label,
    required this.onTap,
  });
}

@immutable
class GigvoraProfileUtility {
  final IconData icon;
  final String label;
  final VoidCallback onTap;

  const GigvoraProfileUtility({
    required this.icon,
    required this.label,
    required this.onTap,
  });
}

@immutable
class GigvoraProfileShell extends StatelessWidget {
  final Widget hero;
  final Widget tabs;
  final List<GigvoraProfileQuickLink> quickLinks;
  final List<GigvoraProfileUtility> utilities;
  final Widget aboutCard;
  final Widget? mediaPreview;
  final Widget? friendsPreview;
  final List<Widget> mainContent;
  final Widget? interviewTimeline;
  final Widget? interviewReminders;

  const GigvoraProfileShell({
    super.key,
    required this.hero,
    required this.tabs,
    required this.quickLinks,
    required this.utilities,
    required this.aboutCard,
    required this.mainContent,
    this.mediaPreview,
    this.friendsPreview,
    this.interviewTimeline,
    this.interviewReminders,
  });

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          hero,
          const SizedBox(height: 16),
          tabs,
          const SizedBox(height: 24),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                flex: 2,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    ...mainContent.map(
                      (widget) => Padding(
                        padding: const EdgeInsets.only(bottom: 16),
                        child: widget,
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 24),
              Flexible(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    aboutCard,
                    const SizedBox(height: 16),
                    _QuickLinksCard(items: quickLinks),
                    const SizedBox(height: 16),
                    _UtilitiesCard(items: utilities),
                    if (interviewTimeline != null) ...[
                      const SizedBox(height: 16),
                      interviewTimeline!,
                    ],
                    if (interviewReminders != null) ...[
                      const SizedBox(height: 16),
                      interviewReminders!,
                    ],
                    if (mediaPreview != null) ...[
                      const SizedBox(height: 16),
                      mediaPreview!,
                    ],
                    if (friendsPreview != null) ...[
                      const SizedBox(height: 16),
                      friendsPreview!,
                    ],
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class _QuickLinksCard extends StatelessWidget {
  const _QuickLinksCard({required this.items});

  final List<GigvoraProfileQuickLink> items;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      elevation: 1,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: items
              .map(
                (item) => ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: Icon(item.icon, color: theme.colorScheme.primary),
                  title: Text(item.label, style: theme.textTheme.bodyLarge),
                  onTap: item.onTap,
                ),
              )
              .toList(),
        ),
      ),
    );
  }
}

class _UtilitiesCard extends StatelessWidget {
  const _UtilitiesCard({required this.items});

  final List<GigvoraProfileUtility> items;

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      elevation: 1,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Wrap(
          spacing: 12,
          runSpacing: 12,
          children: items
              .map(
                (item) => ActionChip(
                  avatar: Icon(item.icon, size: 16),
                  label: Text(item.label),
                  onPressed: item.onTap,
                ),
              )
              .toList(),
        ),
      ),
    );
  }
}

