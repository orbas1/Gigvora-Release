import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

@immutable
class GigvoraQuickToolAction {
  final String id;
  final String label;
  final String description;
  final String href;
  final String? iconKey;

  const GigvoraQuickToolAction({
    required this.id,
    required this.label,
    required this.description,
    required this.href,
    this.iconKey,
  });

  factory GigvoraQuickToolAction.fromJson(Map<String, dynamic> json) {
    return GigvoraQuickToolAction(
      id: json['id'] as String? ?? '',
      label: json['label'] as String? ?? '',
      description: json['description'] as String? ?? '',
      href: json['href'] as String? ?? '',
      iconKey: json['icon_key'] as String?,
    );
  }
}

@immutable
class GigvoraQuickToolsPayload {
  final String context;
  final String title;
  final String description;
  final List<GigvoraQuickToolAction> actions;

  const GigvoraQuickToolsPayload({
    required this.context,
    required this.title,
    required this.description,
    required this.actions,
  });

  factory GigvoraQuickToolsPayload.fromJson(Map<String, dynamic> json) {
    final actionsJson = json['actions'] as List<dynamic>? ?? const [];
    return GigvoraQuickToolsPayload(
      context: json['context'] as String? ?? 'global',
      title: json['label'] as String? ?? 'Utilities',
      description: json['description'] as String? ?? '',
      actions: actionsJson
          .map((item) => GigvoraQuickToolAction.fromJson(item as Map<String, dynamic>))
          .toList(),
    );
  }
}

class GigvoraQuickToolsClient {
  final String baseUrl;
  final Future<String?> Function()? tokenProvider;
  final http.Client _http;

  GigvoraQuickToolsClient({
    required this.baseUrl,
    this.tokenProvider,
    http.Client? httpClient,
  }) : _http = httpClient ?? http.Client();

  Future<GigvoraQuickToolsPayload> fetch({
    String context = 'global',
    Duration timeout = const Duration(seconds: 15),
  }) async {
    final uri = Uri.parse('$baseUrl/utilities/quick-tools').replace(
      queryParameters: {'context': context},
    );
    final headers = <String, String>{'Accept': 'application/json'};
    final token = tokenProvider != null ? await tokenProvider!.call() : null;
    if (token != null && token.isNotEmpty) {
      headers['Authorization'] = 'Bearer $token';
    }

    final response = await _http.get(uri, headers: headers).timeout(timeout);

    if (response.statusCode < 200 || response.statusCode >= 300) {
      throw GigvoraQuickToolsException(
        'Unable to fetch quick tools (${response.statusCode})',
      );
    }

    final payload = jsonDecode(response.body) as Map<String, dynamic>;
    return GigvoraQuickToolsPayload.fromJson(payload);
  }
}

class GigvoraQuickToolsException implements Exception {
  final String message;

  GigvoraQuickToolsException(this.message);

  @override
  String toString() => message;
}

class GigvoraQuickToolsPanel extends StatelessWidget {
  final String title;
  final String description;
  final List<GigvoraQuickToolAction> actions;
  final void Function(GigvoraQuickToolAction action)? onActionTap;

  const GigvoraQuickToolsPanel({
    super.key,
    required this.title,
    required this.description,
    required this.actions,
    this.onActionTap,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: theme.textTheme.titleLarge),
            if (description.isNotEmpty) ...[
              const SizedBox(height: 8),
              Text(description, style: theme.textTheme.bodyMedium),
            ],
            const SizedBox(height: 16),
            Wrap(
              spacing: 12,
              runSpacing: 12,
              children: actions
                  .map(
                    (action) => ActionChip(
                      avatar: Icon(_iconForKey(action.iconKey), size: 16),
                      label: Text(action.label),
                      onPressed: () => onActionTap?.call(action),
                    ),
                  )
                  .toList(),
            ),
          ],
        ),
      ),
    );
  }

  IconData _iconForKey(String? key) {
    switch (key) {
      case 'bell':
        return Icons.notifications_outlined;
      case 'bookmark':
        return Icons.bookmark_outline;
      case 'calendar':
        return Icons.calendar_today_outlined;
      case 'poll':
        return Icons.poll_outlined;
      case 'thread':
        return Icons.dynamic_feed_outlined;
      case 'clock':
        return Icons.alarm_outlined;
      case 'wand':
        return Icons.auto_awesome;
      case 'id-card':
        return Icons.badge_outlined;
      case 'hashtag':
        return Icons.tag_outlined;
      case 'briefcase':
        return Icons.work_outline;
      case 'handshake':
        return Icons.groups_outlined;
      case 'scale':
        return Icons.account_balance_outlined;
      case 'broadcast':
        return Icons.cast_connected;
      case 'analytics':
        return Icons.analytics_outlined;
      case 'shield':
        return Icons.shield_outlined;
      case 'gavel':
        return Icons.gavel_outlined;
      case 'bullhorn':
        return Icons.campaign_outlined;
      default:
        return Icons.flash_on_outlined;
    }
  }
}


