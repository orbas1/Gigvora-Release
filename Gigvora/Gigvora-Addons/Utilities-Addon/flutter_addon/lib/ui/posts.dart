import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../state/state.dart';
import 'common.dart';

class CreatePollScreen extends StatefulWidget {
  final PostsEnhancementState? state;
  final AnalyticsClient analytics;

  const CreatePollScreen({super.key, required this.analytics, this.state});

  @override
  State<CreatePollScreen> createState() => _CreatePollScreenState();
}

class _CreatePollScreenState extends State<CreatePollScreen> {
  final _question = TextEditingController();
  final List<TextEditingController> _options =
      List.generate(2, (_) => TextEditingController());
  PostsEnhancementState get state => widget.state ?? context.read<PostsEnhancementState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('CreatePoll', {});
  }

  @override
  void dispose() {
    _question.dispose();
    for (final c in _options) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _save() async {
    final payload = {
      'question': _question.text.trim(),
      'options': _options.map((o) => o.text.trim()).where((o) => o.isNotEmpty).toList(),
    };
    await state.createPoll(payload);
    widget.analytics.trackAction('poll_created', {'question': _question.text});
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Create Poll')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _question,
              decoration: const InputDecoration(labelText: 'Question'),
            ),
            const SizedBox(height: 12),
            ..._options
                .asMap()
                .entries
                .map(
                  (entry) => TextField(
                    controller: entry.value,
                    decoration: InputDecoration(labelText: 'Option ${entry.key + 1}'),
                  ),
                )
                .toList(),
            const SizedBox(height: 16),
            Consumer<PostsEnhancementState>(
              builder: (context, s, _) {
                if (s.loading) return const LoadingView(message: 'Posting poll...');
                if (s.error != null) return ErrorView(message: s.error!, onRetry: _save);
                return ElevatedButton(onPressed: _save, child: const Text('Publish'));
              },
            ),
          ],
        ),
      ),
    );
  }
}

class ThreadedPostScreen extends StatefulWidget {
  final PostsEnhancementState? state;
  final AnalyticsClient analytics;

  const ThreadedPostScreen({super.key, required this.analytics, this.state});

  @override
  State<ThreadedPostScreen> createState() => _ThreadedPostScreenState();
}

class _ThreadedPostScreenState extends State<ThreadedPostScreen> {
  final List<TextEditingController> _segments =
      List.generate(2, (_) => TextEditingController());
  PostsEnhancementState get state => widget.state ?? context.read<PostsEnhancementState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ThreadedPost', {});
  }

  @override
  void dispose() {
    for (final c in _segments) {
      c.dispose();
    }
    super.dispose();
  }

  Future<void> _publish() async {
    final payload = {
      'segments': _segments.map((s) => s.text.trim()).where((s) => s.isNotEmpty).toList(),
    };
    await state.createThread(payload);
    widget.analytics.trackAction('thread_created', {'segments': payload['segments']});
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Threaded Post')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            ..._segments
                .asMap()
                .entries
                .map(
                  (entry) => Padding(
                    padding: const EdgeInsets.only(bottom: 8),
                    child: TextField(
                      controller: entry.value,
                      maxLines: 3,
                      decoration: InputDecoration(labelText: 'Segment ${entry.key + 1}'),
                    ),
                  ),
                )
                .toList(),
            Consumer<PostsEnhancementState>(
              builder: (context, s, _) {
                if (s.loading) return const LoadingView(message: 'Posting thread...');
                if (s.error != null) return ErrorView(message: s.error!, onRetry: _publish);
                return ElevatedButton(onPressed: _publish, child: const Text('Publish')); 
              },
            ),
          ],
        ),
      ),
    );
  }
}

class ReshareSheet extends StatefulWidget {
  final PostsEnhancementState? state;
  final AnalyticsClient analytics;

  const ReshareSheet({super.key, required this.analytics, this.state});

  @override
  State<ReshareSheet> createState() => _ReshareSheetState();
}

class _ReshareSheetState extends State<ReshareSheet> {
  final _comment = TextEditingController();
  PostsEnhancementState get state => widget.state ?? context.read<PostsEnhancementState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('ReshareSheet', {});
  }

  @override
  void dispose() {
    _comment.dispose();
    super.dispose();
  }

  Future<void> _reshare() async {
    final payload = {'comment': _comment.text.trim()};
    await state.reshare(payload);
    widget.analytics.trackAction('post_reshared', payload);
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).viewInsets.bottom,
        left: 16,
        right: 16,
        top: 24,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          TextField(
            controller: _comment,
            maxLines: 3,
            decoration: const InputDecoration(labelText: 'Add a comment'),
          ),
          const SizedBox(height: 12),
          Consumer<PostsEnhancementState>(
            builder: (context, s, _) {
              if (s.loading) return const LoadingView(message: 'Sharing...');
              if (s.error != null) return ErrorView(message: s.error!, onRetry: _reshare);
              return ElevatedButton.icon(
                onPressed: _reshare,
                icon: const Icon(Icons.repeat),
                label: const Text('Reshare'),
              );
            },
          ),
        ],
      ),
    );
  }
}

class CelebrateOccasionComposer extends StatefulWidget {
  final PostsEnhancementState? state;
  final AnalyticsClient analytics;

  const CelebrateOccasionComposer({super.key, required this.analytics, this.state});

  @override
  State<CelebrateOccasionComposer> createState() => _CelebrateOccasionComposerState();
}

class _CelebrateOccasionComposerState extends State<CelebrateOccasionComposer> {
  final _message = TextEditingController();
  PostsEnhancementState get state => widget.state ?? context.read<PostsEnhancementState>();

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('CelebrateComposer', {});
  }

  @override
  void dispose() {
    _message.dispose();
    super.dispose();
  }

  Future<void> _publish() async {
    final payload = {'message': _message.text.trim()};
    await state.celebrate(payload);
    widget.analytics.trackAction('celebrate_post', payload);
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Celebrate an Occasion')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _message,
              maxLines: 4,
              decoration: const InputDecoration(labelText: 'Share your celebration'),
            ),
            const SizedBox(height: 12),
            Consumer<PostsEnhancementState>(
              builder: (context, s, _) {
                if (s.loading) return const LoadingView(message: 'Posting...');
                if (s.error != null) return ErrorView(message: s.error!, onRetry: _publish);
                return ElevatedButton(onPressed: _publish, child: const Text('Post'));
              },
            ),
          ],
        ),
      ),
    );
  }
}
