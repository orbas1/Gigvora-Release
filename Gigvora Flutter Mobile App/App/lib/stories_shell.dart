import 'package:flutter/material.dart';

@immutable
class GigvoraStoryQuickTool {
  final IconData icon;
  final String label;
  final VoidCallback onTap;

  const GigvoraStoryQuickTool({
    required this.icon,
    required this.label,
    required this.onTap,
  });
}

@immutable
class GigvoraStoryRail extends StatelessWidget {
  final List<Widget> stories;
  final List<GigvoraStoryQuickTool> quickTools;
  final Widget? storyViewer;

  const GigvoraStoryRail({
    super.key,
    required this.stories,
    required this.quickTools,
    this.storyViewer,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(
              height: 160,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                itemBuilder: (context, index) => stories[index],
                separatorBuilder: (_, __) => const SizedBox(width: 12),
                itemCount: stories.length,
              ),
            ),
            const SizedBox(height: 16),
            Wrap(
              spacing: 12,
              runSpacing: 12,
              children: quickTools
                  .map(
                    (tool) => OutlinedButton.icon(
                      onPressed: tool.onTap,
                      icon: Icon(tool.icon, size: 16),
                      label: Text(tool.label),
                    ),
                  )
                  .toList(),
            ),
            if (storyViewer != null) ...[
              const SizedBox(height: 24),
              ClipRRect(
                borderRadius: BorderRadius.circular(20),
                child: storyViewer!,
              ),
            ],
          ],
        ),
      ),
    );
  }
}

