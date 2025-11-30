import 'package:flutter/material.dart';

@immutable
class GigvoraNotificationItem {
  final String title;
  final String body;
  final String typeLabel;
  final bool unread;
  final VoidCallback? onTap;
  final VoidCallback? onDismiss;

  const GigvoraNotificationItem({
    required this.title,
    required this.body,
    required this.typeLabel,
    this.unread = false,
    this.onTap,
    this.onDismiss,
  });
}

class GigvoraNotificationsPanel extends StatelessWidget {
  final List<GigvoraNotificationItem> items;
  final String header;

  const GigvoraNotificationsPanel({
    super.key,
    required this.items,
    this.header = '',
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (header.isNotEmpty)
              Padding(
                padding: const EdgeInsets.only(bottom: 12),
                child: Text(header, style: theme.textTheme.titleMedium),
              ),
            ...items.map((item) => _NotificationRow(item: item)).toList(),
          ],
        ),
      ),
    );
  }
}

class _NotificationRow extends StatelessWidget {
  final GigvoraNotificationItem item;

  const _NotificationRow({required this.item});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return ListTile(
      contentPadding: EdgeInsets.zero,
      title: Text(
        item.title,
        style: theme.textTheme.bodyLarge?.copyWith(
          fontWeight: item.unread ? FontWeight.w600 : FontWeight.w500,
        ),
      ),
      subtitle: Text(item.body),
      leading: CircleAvatar(
        backgroundColor: theme.colorScheme.primary.withValues(alpha: 0.1),
        foregroundColor: theme.colorScheme.primary,
        child: Text(
          item.typeLabel.characters.first.toUpperCase(),
          style: theme.textTheme.labelLarge,
        ),
      ),
      trailing: IconButton(
        icon: const Icon(Icons.close),
        onPressed: item.onDismiss,
      ),
      onTap: item.onTap,
    );
  }
}

@immutable
class GigvoraInboxComposerAction {
  final IconData icon;
  final String tooltip;
  final VoidCallback onPressed;

  const GigvoraInboxComposerAction({
    required this.icon,
    required this.tooltip,
    required this.onPressed,
  });
}

class GigvoraInboxComposer extends StatefulWidget {
  final TextEditingController controller;
  final VoidCallback onSend;
  final List<GigvoraInboxComposerAction> actions;

  const GigvoraInboxComposer({
    super.key,
    required this.controller,
    required this.onSend,
    this.actions = const [],
  });

  @override
  State<GigvoraInboxComposer> createState() => _GigvoraInboxComposerState();
}

class _GigvoraInboxComposerState extends State<GigvoraInboxComposer> {
  bool _sending = false;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: widget.actions
                  .map(
                    (action) => IconButton(
                      tooltip: action.tooltip,
                      icon: Icon(action.icon),
                      onPressed: action.onPressed,
                    ),
                  )
                  .toList(),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: widget.controller,
              maxLines: null,
              decoration: InputDecoration(
                hintText: 'Type a message…',
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(20),
                  borderSide: BorderSide(color: theme.dividerColor),
                ),
              ),
            ),
            const SizedBox(height: 12),
            Align(
              alignment: Alignment.centerRight,
              child: FilledButton.icon(
                onPressed: _sending
                    ? null
                    : () async {
                        setState(() => _sending = true);
                        await Future<void>.delayed(const Duration(milliseconds: 250));
                        widget.onSend();
                        setState(() => _sending = false);
                      },
                icon: const Icon(Icons.send),
                label: Text(_sending ? 'Sending…' : 'Send'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

