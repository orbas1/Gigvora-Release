import 'package:flutter/material.dart';

import '../../services/webinar_service.dart';
import '../../state/webinar_state.dart';
import '../../widgets/live_cards.dart';
import '../../theme/live_mobile_theme.dart';

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
    final registration = _state.registration;
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
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(webinar.title, style: Theme.of(context).textTheme.headlineSmall),
                                const SizedBox(height: 6),
                                Text(
                                  '${webinar.startsAt} • ${(webinar.endsAt.difference(webinar.startsAt).inMinutes)} mins',
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodyMedium
                                      ?.copyWith(color: LiveMobileTheme.mutedText(context)),
                                ),
                              ],
                            ),
                          ),
                          Chip(
                            backgroundColor: webinar.isLive
                                ? Theme.of(context).colorScheme.errorContainer
                                : Theme.of(context).colorScheme.secondaryContainer,
                            label: Text(webinar.isLive ? 'Live now' : 'Scheduled'),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Card(
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text('Overview', style: Theme.of(context).textTheme.titleMedium),
                              const SizedBox(height: 8),
                              Text(webinar.description ?? 'No description',
                                  style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                                        color: LiveMobileTheme.mutedText(context),
                                      )),
                              const SizedBox(height: 12),
                              Wrap(
                                spacing: 8,
                                runSpacing: 8,
                                children: [
                                  InfoChip(label: webinar.isPaid ? 'Paid ticket' : 'Free'),
                                  InfoChip(label: (webinar.host?['name'] as String?) ?? 'Host pending'),
                                  InfoChip(label: 'Replay shared after the session'),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
                      Card(
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        child: Padding(
                          padding: const EdgeInsets.all(16),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text('Readiness checklist', style: Theme.of(context).textTheme.titleMedium),
                              const SizedBox(height: 8),
                              const ListTile(
                                leading: Icon(Icons.mic_none_outlined),
                                title: Text('Test mic and camera before going live'),
                              ),
                              const ListTile(
                                leading: Icon(Icons.schedule_outlined),
                                title: Text('Join a few minutes early to sync reminders'),
                              ),
                              const ListTile(
                                leading: Icon(Icons.security_outlined),
                                title: Text('Follow chat etiquette; hosts can moderate'),
                              ),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 16),
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
                          if (registration == null) {
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
