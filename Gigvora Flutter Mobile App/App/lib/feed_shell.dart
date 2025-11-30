import 'package:flutter/material.dart';

@immutable
class GigvoraFeedComposerAction {
  final IconData icon;
  final String label;
  final String description;
  final VoidCallback onTap;
  final Color? accentColor;

  const GigvoraFeedComposerAction({
    required this.icon,
    required this.label,
    required this.description,
    required this.onTap,
    this.accentColor,
  });
}

@immutable
class GigvoraFeedRecommendationLaneItem {
  final String title;
  final String? subtitle;
  final String? meta;
  final VoidCallback onTap;

  const GigvoraFeedRecommendationLaneItem({
    required this.title,
    required this.onTap,
    this.subtitle,
    this.meta,
  });
}

@immutable
class GigvoraFeedRecommendationLane {
  final IconData icon;
  final String label;
  final List<GigvoraFeedRecommendationLaneItem> items;

  const GigvoraFeedRecommendationLane({
    required this.icon,
    required this.label,
    required this.items,
  });
}

/// High-level container that mirrors the redesigned Gigvora feed hub.
///
/// The shell renders the optional stories rail, composer, recommendation lanes
/// (jobs, freelance, live, utilities) and any custom feed widgets provided by
/// the host application. This keeps the Flutter experience in lockstep with
/// the Laravel/Blade layout described in `logic_flows.md#1.2 Live Feed`.
class GigvoraFeedShell extends StatelessWidget {
  final Widget? stories;
  final List<GigvoraFeedComposerAction> composerActions;
  final Widget? mediaStudio;
  final List<GigvoraFeedRecommendationLane> recommendationLanes;
  final List<Widget> feedCards;
  final Widget? advertisement;
  final Widget? headerActions;
  final Widget? quickToolsPanel;
  final Widget? interviewTimeline;
  final Widget? interviewReminders;

  const GigvoraFeedShell({
    super.key,
    required this.composerActions,
    required this.recommendationLanes,
    required this.feedCards,
    this.mediaStudio,
    this.stories,
    this.advertisement,
    this.headerActions,
    this.quickToolsPanel,
    this.interviewTimeline,
    this.interviewReminders,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          _FeedHeader(actions: headerActions),
          const SizedBox(height: 24),
          if (stories != null) ...[
            stories!,
            const SizedBox(height: 24),
          ],
          _Composer(actions: composerActions),
          const SizedBox(height: 24),
          if (mediaStudio != null) ...[
            mediaStudio!,
            const SizedBox(height: 24),
          ],
          if (quickToolsPanel != null) ...[
            quickToolsPanel!,
            const SizedBox(height: 24),
          ],
          if (interviewTimeline != null) ...[
            interviewTimeline!,
            const SizedBox(height: 24),
          ],
          if (interviewReminders != null) ...[
            interviewReminders!,
            const SizedBox(height: 24),
          ],
          if (recommendationLanes.isNotEmpty) ...[
            _RecommendationLanes(
              lanes: recommendationLanes,
              theme: theme,
            ),
            const SizedBox(height: 24),
          ],
          if (advertisement != null) ...[
            advertisement!,
            const SizedBox(height: 24),
          ],
          ...feedCards.map((card) => Padding(
                padding: const EdgeInsets.only(bottom: 16),
                child: card,
              )),
        ],
      ),
    );
  }
}

class _FeedHeader extends StatelessWidget {
  const _FeedHeader({required this.actions});

  final Widget? actions;

  @override
  Widget build(BuildContext context) {
    final textTheme = Theme.of(context).textTheme;

    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Live Feed',
                style: textTheme.labelLarge?.copyWith(
                  letterSpacing: 0.2,
                  color: Theme.of(context).colorScheme.primary,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                'Gigvora hub',
                style: textTheme.headlineMedium,
              ),
              const SizedBox(height: 4),
              Text(
                'Posts, jobs, gigs, live sessions and utilities—now unified.',
                style: textTheme.bodyMedium,
              ),
            ],
          ),
        ),
        if (actions != null) ...[
          const SizedBox(width: 16),
          actions!,
        ],
      ],
    );
  }
}

class _Composer extends StatelessWidget {
  const _Composer({required this.actions});

  final List<GigvoraFeedComposerAction> actions;

  @override
  Widget build(BuildContext context) {
    final chips = actions
        .map(
          (action) => Expanded(
            child: InkWell(
              borderRadius: BorderRadius.circular(16),
              onTap: action.onTap,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: Theme.of(context).dividerColor,
                  ),
                  color: Theme.of(context).colorScheme.surface,
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Icon(
                          action.icon,
                          color: action.accentColor ?? Theme.of(context).colorScheme.primary,
                          size: 20,
                        ),
                        const SizedBox(width: 8),
                        Expanded(
                          child: Text(
                            action.label,
                            style: Theme.of(context).textTheme.titleMedium,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    Text(
                      action.description,
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                  ],
                ),
              ),
            ),
          ),
        )
        .toList();

    return Column(
      children: [
        Row(
          children: [
            const CircleAvatar(radius: 24),
            const SizedBox(width: 12),
            Expanded(
              child: Text(
                "What's on your mind?",
                style: Theme.of(context).textTheme.bodyMedium,
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        Wrap(
          spacing: 12,
          runSpacing: 12,
          children: chips,
        ),
      ],
    );
  }
}

class _RecommendationLanes extends StatelessWidget {
  const _RecommendationLanes({
    required this.lanes,
    required this.theme,
  });

  final List<GigvoraFeedRecommendationLane> lanes;
  final ThemeData theme;

  @override
  Widget build(BuildContext context) {
    return Wrap(
      spacing: 16,
      runSpacing: 16,
      children: lanes
          .map(
            (lane) => SizedBox(
              width: MediaQuery.of(context).size.width > 640
                  ? (MediaQuery.of(context).size.width - 64) / 2
                  : double.infinity,
              child: Card(
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20),
                ),
                elevation: 2,
                child: Padding(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Icon(lane.icon, color: theme.colorScheme.primary),
                          const SizedBox(width: 8),
                          Text(
                            lane.label,
                            style: theme.textTheme.titleMedium,
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      ...lane.items.map(
                        (item) => ListTile(
                          contentPadding: EdgeInsets.zero,
                          title: Text(item.title, style: theme.textTheme.bodyLarge),
                          subtitle: item.subtitle != null ? Text(item.subtitle!) : null,
                          trailing: item.meta != null ? Text(item.meta!, style: theme.textTheme.bodySmall) : null,
                          onTap: item.onTap,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          )
          .toList(),
    );
  }
}

