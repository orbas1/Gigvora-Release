import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class NewsletterSettingsScreen extends StatefulWidget {
  final NewsletterState? state;
  final AnalyticsClient analytics;

  const NewsletterSettingsScreen({super.key, required this.analytics, this.state});

  @override
  State<NewsletterSettingsScreen> createState() => _NewsletterSettingsScreenState();
}

class _NewsletterSettingsScreenState extends State<NewsletterSettingsScreen> {
  final TextEditingController _email = TextEditingController();
  NewsletterState get state => widget.state ?? context.read<NewsletterState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('NewsletterSettings', {});
  }

  @override
  void dispose() {
    _email.dispose();
    super.dispose();
  }

  Future<void> _subscribe() async {
    await state.subscribe(_email.text.trim());
    widget.analytics.trackAction('newsletter_subscribed', {'email': _email.text.trim()});
  }

  Future<void> _unsubscribe() async {
    await state.unsubscribe(_email.text.trim());
    widget.analytics.trackAction('newsletter_unsubscribed', {'email': _email.text.trim()});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Newsletters')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            TextField(
              controller: _email,
              decoration: const InputDecoration(labelText: 'Email'),
            ),
            const SizedBox(height: 12),
            Consumer<NewsletterState>(
              builder: (context, s, _) {
                if (s.loading) return const LoadingView();
                if (s.error != null) return ErrorView(message: s.error!, onRetry: _subscribe);
                return Row(
                  children: [
                    ElevatedButton(onPressed: _subscribe, child: const Text('Subscribe')),
                    const SizedBox(width: 8),
                    TextButton(onPressed: _unsubscribe, child: const Text('Unsubscribe')),
                  ],
                );
              },
            ),
            const SizedBox(height: 16),
            Consumer<NewsletterState>(
              builder: (context, s, _) {
                if (s.subscription == null) {
                  return const Text('No subscription yet');
                }
                return Text('Subscribed as ${s.subscription!.email}');
              },
            )
          ],
        ),
      ),
    );
  }
}
