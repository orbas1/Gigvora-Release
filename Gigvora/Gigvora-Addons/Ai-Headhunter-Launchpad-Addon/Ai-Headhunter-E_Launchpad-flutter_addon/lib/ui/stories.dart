import 'dart:async';

import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../analytics/analytics_client.dart';
import '../models/models.dart';
import '../services/music_library_api.dart';
import '../state/state.dart';
import 'common.dart';

class StoriesViewerScreen extends StatefulWidget {
  final StoriesState? state;
  final AnalyticsClient analytics;
  final int storyId;

  const StoriesViewerScreen({
    super.key,
    required this.analytics,
    required this.storyId,
    this.state,
  });

  @override
  State<StoriesViewerScreen> createState() => _StoriesViewerScreenState();
}

class _StoriesViewerScreenState extends State<StoriesViewerScreen> {
  StoriesState get state => widget.state ?? context.read<StoriesState>();
  final PageController _controller = PageController();
  Timer? _timer;
  double _progress = 0;
  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('StoriesViewer', {'storyId': widget.storyId});
    _load();
  }

  Future<void> _load() async {
    await state.loadStories();
    if (!mounted) return;
    if (state.stories.isEmpty) {
      setState(() {});
      return;
    }
    _currentIndex = state.stories.indexWhere((s) => s.id == widget.storyId);
    if (_currentIndex < 0) _currentIndex = 0;
    _controller.jumpToPage(_currentIndex);
    _restartProgress();
  }

  void _restartProgress() {
    _timer?.cancel();
    _progress = 0;
    _timer = Timer.periodic(const Duration(milliseconds: 80), (timer) {
      setState(() {
        _progress += 0.04;
        if (_progress >= 1) {
          _next();
        }
      });
    });
  }

  void _next() {
    if (_currentIndex < state.stories.length - 1) {
      _currentIndex++;
      _controller.animateToPage(
        _currentIndex,
        duration: const Duration(milliseconds: 250),
        curve: Curves.easeInOut,
      );
      _restartProgress();
    } else {
      _timer?.cancel();
      Navigator.of(context).maybePop();
    }
  }

  void _previous() {
    if (_currentIndex > 0) {
      _currentIndex--;
      _controller.animateToPage(
        _currentIndex,
        duration: const Duration(milliseconds: 250),
        curve: Curves.easeInOut,
      );
      _restartProgress();
    }
  }

  @override
  void dispose() {
    _timer?.cancel();
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: SafeArea(
        child: Consumer<StoriesState>(
          builder: (context, s, _) {
            if (s.loading && s.stories.isEmpty) {
              return const LoadingView(message: 'Loading stories...');
            }
            if (s.error != null) {
              return ErrorView(message: s.error!, onRetry: _load);
            }
            if (s.stories.isEmpty) {
              return const EmptyView(message: 'No stories to show');
            }
            final story = s.stories[_currentIndex];
            return GestureDetector(
              onTapUp: (details) {
                final width = MediaQuery.of(context).size.width;
                if (details.globalPosition.dx > width / 2) {
                  _next();
                } else {
                  _previous();
                }
              },
              child: Stack(
                children: [
                  PageView.builder(
                    controller: _controller,
                    onPageChanged: (i) {
                      setState(() => _currentIndex = i);
                      _restartProgress();
                    },
                    itemCount: s.stories.length,
                    itemBuilder: (context, index) {
                      final item = s.stories[index];
                      return Container(
                        decoration: BoxDecoration(
                          image: DecorationImage(
                            image: NetworkImage(item.mediaUrl),
                            fit: BoxFit.cover,
                          ),
                        ),
                        child: Container(
                          decoration: const BoxDecoration(
                            gradient: LinearGradient(
                              colors: [Colors.black54, Colors.transparent, Colors.black54],
                              begin: Alignment.topCenter,
                              end: Alignment.bottomCenter,
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                  Positioned(
                    top: 12,
                    left: 12,
                    right: 12,
                    child: Column(
                      children: [
                        Row(
                          children: List.generate(s.stories.length, (i) {
                            return Expanded(
                              child: Padding(
                                padding: const EdgeInsets.symmetric(horizontal: 2),
                                child: LinearProgressIndicator(
                                  value: i < _currentIndex
                                      ? 1
                                      : i == _currentIndex
                                          ? _progress.clamp(0.0, 1.0)
                                          : 0,
                                  backgroundColor: Colors.white24,
                                  color: Colors.white,
                                  minHeight: 3,
                                ),
                              ),
                            );
                          }),
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const CircleAvatar(
                              radius: 18,
                              child: Icon(Icons.person, color: Colors.white),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    'Story ${story.id}',
                                    style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                                  ),
                                  if (story.metadata.caption != null)
                                    Text(
                                      story.metadata.caption!,
                                      style: const TextStyle(color: Colors.white70),
                                    ),
                                ],
                              ),
                            ),
                            IconButton(
                              color: Colors.white,
                              onPressed: () => Navigator.of(context).maybePop(),
                              icon: const Icon(Icons.close),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  Positioned(
                    bottom: 32,
                    left: 16,
                    right: 16,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (story.metadata.track != null)
                          Chip(
                            backgroundColor: Colors.black54,
                            label: Text(
                              '♪ ${story.metadata.track!.title} – ${story.metadata.track!.artist}',
                              style: const TextStyle(color: Colors.white),
                            ),
                          ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            TextButton.icon(
                              onPressed: () async {
                                await state.loadViewers(story.id);
                                if (!mounted) return;
                                showModalBottomSheet(
                                  context: context,
                                  builder: (_) => _ViewersSheet(viewers: state.currentViewers),
                                );
                              },
                              icon: const Icon(Icons.remove_red_eye, color: Colors.white),
                              label: Text(
                                '${story.viewers.length} viewers',
                                style: const TextStyle(color: Colors.white),
                              ),
                            ),
                            const Spacer(),
                            ElevatedButton.icon(
                              onPressed: _next,
                              icon: const Icon(Icons.navigate_next),
                              label: const Text('Next'),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}

class _ViewersSheet extends StatelessWidget {
  final List<StoryViewer> viewers;

  const _ViewersSheet({required this.viewers});

  @override
  Widget build(BuildContext context) {
    if (viewers.isEmpty) {
      return const Padding(
        padding: EdgeInsets.all(24),
        child: Text('No viewers yet'),
      );
    }
    return ListView(
      children: viewers
          .map(
            (v) => ListTile(
              leading: CircleAvatar(
                backgroundImage: v.avatarUrl != null ? NetworkImage(v.avatarUrl!) : null,
                child: v.avatarUrl == null ? const Icon(Icons.person_outline) : null,
              ),
              title: Text(v.name ?? 'User ${v.userId}'),
              subtitle: Text(v.viewedAt?.toLocal().toString() ?? ''),
            ),
          )
          .toList(),
    );
  }
}

class StoryCreatorScreen extends StatefulWidget {
  final StoryCreationState? state;
  final StoriesState? viewerState;
  final AnalyticsClient analytics;
  final MusicLibraryApi? musicApi;

  const StoryCreatorScreen({
    super.key,
    required this.analytics,
    this.state,
    this.viewerState,
    this.musicApi,
  });

  @override
  State<StoryCreatorScreen> createState() => _StoryCreatorScreenState();
}

class _StoryCreatorScreenState extends State<StoryCreatorScreen> {
  final _caption = TextEditingController();
  MusicTrack? _selectedTrack;
  StoryCreationState get state => widget.state ?? context.read<StoryCreationState>();
  MusicLibraryApi? get musicApi => widget.musicApi;
  bool _loadingTracks = false;
  List<MusicTrack> _tracks = [];
  bool _allowReplies = true;
  bool _enableStickers = true;
  double _durationSeconds = 8;
  Color _background = Colors.black87;

  @override
  void initState() {
    super.initState();
    widget.analytics.trackScreen('StoryCreator', {});
    _loadTracks();
  }

  @override
  void dispose() {
    _caption.dispose();
    super.dispose();
  }

  Future<void> _loadTracks() async {
    if (musicApi == null) return;
    setState(() => _loadingTracks = true);
    try {
      _tracks = await musicApi!.index();
    } catch (e) {
      _tracks = [];
    } finally {
      setState(() => _loadingTracks = false);
    }
  }

  Future<void> _save() async {
    final payload = {
      'caption': _caption.text.trim(),
      if (_selectedTrack != null) 'music_track_id': _selectedTrack!.id,
      'allow_replies': _allowReplies,
      'stickers_enabled': _enableStickers,
      'duration_seconds': _durationSeconds.round(),
      'background': '#${_background.value.toRadixString(16)}',
    };
    await state.createOrUpdate(payload);
    widget.analytics.trackAction('story_created', payload);
    if (mounted) Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Create Story')),
      body: Padding(
        padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                TextField(
                  controller: _caption,
                  maxLines: 3,
                  decoration: const InputDecoration(labelText: 'Caption & overlays'),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    const Text('Duration'),
                    Expanded(
                      child: Slider(
                        value: _durationSeconds,
                        divisions: 10,
                        onChanged: (v) => setState(() => _durationSeconds = v),
                        label: '${_durationSeconds.round()}s',
                        min: 5,
                        max: 20,
                      ),
                    ),
                  ],
                ),
                SwitchListTile(
                  value: _allowReplies,
                  onChanged: (v) => setState(() => _allowReplies = v),
                  title: const Text('Allow message replies'),
                ),
                SwitchListTile(
                  value: _enableStickers,
                  onChanged: (v) => setState(() => _enableStickers = v),
                  title: const Text('Enable stickers/filters'),
                ),
                Row(
                  children: [
                    const Text('Background'),
                    const SizedBox(width: 12),
                    ChoiceChip(
                      label: const Text('Dark'),
                      selected: _background == Colors.black87,
                      onSelected: (_) => setState(() => _background = Colors.black87),
                    ),
                    const SizedBox(width: 8),
                    ChoiceChip(
                      label: const Text('Blue'),
                      selected: _background == Colors.blueGrey,
                      onSelected: (_) => setState(() => _background = Colors.blueGrey),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                if (_loadingTracks)
                  const LinearProgressIndicator()
                else if (_tracks.isNotEmpty)
              DropdownButtonFormField<MusicTrack>(
                value: _selectedTrack,
                items: _tracks
                    .map((t) => DropdownMenuItem(value: t, child: Text('${t.title} – ${t.artist}')))
                    .toList(),
                onChanged: (val) => setState(() => _selectedTrack = val),
                decoration: const InputDecoration(labelText: 'Music'),
              ),
            const SizedBox(height: 16),
            Consumer<StoryCreationState>(
              builder: (context, s, _) {
                if (s.loading) return const LoadingView(message: 'Publishing story...');
                if (s.error != null) {
                  return ErrorView(message: s.error!, onRetry: _save);
                }
                return ElevatedButton.icon(
                  onPressed: _save,
                  icon: const Icon(Icons.cloud_upload),
                  label: const Text('Publish Story'),
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}
