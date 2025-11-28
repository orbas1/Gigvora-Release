import 'package:flutter/material.dart';

import '../../services/webinar_service.dart';
import '../../state/webinar_state.dart';
import '../../widgets/live_cards.dart';

class WebinarDetailScreen extends StatefulWidget {
  const WebinarDetailScreen({super.key, required this.service, required this.webinarId});

  final WebinarService service;
  final int webinarId;

  @override
  State<WebinarDetailScreen> createState() => _WebinarDetailScreenState();
}

class _WebinarDetailScreenState extends State<WebinarDetailScreen> {
  late final WebinarState _state;

  @override
  void initState() {
    super.initState();
    _state = WebinarState(widget.service)..addListener(_onState);
    _state.selectWebinar(widget.webinarId);
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final webinar = _state.selected.data;
    return Scaffold(
      appBar: AppBar(title: Text(webinar?.title ?? 'Webinar')),
      body: webinar == null
          ? const Center(child: CircularProgressIndicator())
          : Stack(
              children: [
                SingleChildScrollView(
                  padding: const EdgeInsets.fromLTRB(16, 16, 16, 90),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(webinar.title, style: Theme.of(context).textTheme.headlineSmall),
                      const SizedBox(height: 4),
                      Text('${webinar.startsAt} • ${(webinar.endsAt.difference(webinar.startsAt).inMinutes)} mins',
                          style: Theme.of(context).textTheme.bodyMedium?.copyWith(color: Colors.grey[700])),
                      const SizedBox(height: 16),
                      Text(webinar.description ?? 'No description', style: Theme.of(context).textTheme.bodyLarge),
                      const SizedBox(height: 16),
                      Text('Speakers', style: Theme.of(context).textTheme.titleMedium),
                      const SizedBox(height: 8),
                      Wrap(spacing: 8, runSpacing: 8, children: [const InfoChip(label: 'Host'), const InfoChip(label: 'Guest')]),
                      const SizedBox(height: 24),
                      Text('Past recordings', style: Theme.of(context).textTheme.titleMedium),
                      ...webinar.recordings
                          .map((rec) => ListTile(
                                title: Text(rec.title ?? 'Recording'),
                                subtitle: Text(rec.duration?.toString() ?? ''),
                                trailing: const Icon(Icons.play_circle_outline),
                                onTap: () => Navigator.pushNamed(context, '/live/webinars/recording/${rec.id}', arguments: rec),
                              ))
                          .toList(),
                    ],
                  ),
                ),
                Positioned(
                  left: 0,
                  right: 0,
                  bottom: 0,
                  child: SafeArea(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: ElevatedButton(
                        onPressed: () async {
                          if (_state.registration == null) {
                            await _state.registerForWebinar(widget.webinarId);
                          }
                          final data = _state.selected.data;
                          if (!mounted || data == null) return;
                          ScaffoldMessenger.of(context)
                              .showSnackBar(const SnackBar(content: Text('Registered. Waiting room is ready.')));
                          Navigator.pushNamed(
                            context,
                            '/live/webinars/waiting/${widget.webinarId}',
                            arguments: {
                              'title': data.title,
                              'startsAt': data.startsAt,
                              'message': data.waitingRoomMessage,
                              'isLive': data.isLive,
                            },
                          );
                        },
                        child: Text(_state.registration == null ? 'Register' : 'Join Waiting Room'),
                      ),
                    ),
                  ),
                )
              ],
            ),
    );
  }
}
