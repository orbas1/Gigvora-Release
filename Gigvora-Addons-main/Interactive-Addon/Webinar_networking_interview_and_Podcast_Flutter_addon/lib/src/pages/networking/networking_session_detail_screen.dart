import 'package:flutter/material.dart';

import '../../services/networking_service.dart';
import '../../state/networking_state.dart';
import '../../widgets/live_cards.dart';

class NetworkingSessionDetailScreen extends StatefulWidget {
  const NetworkingSessionDetailScreen({super.key, required this.service, required this.sessionId});

  final NetworkingService service;
  final int sessionId;

  @override
  State<NetworkingSessionDetailScreen> createState() => _NetworkingSessionDetailScreenState();
}

class _NetworkingSessionDetailScreenState extends State<NetworkingSessionDetailScreen> {
  late final NetworkingState _state;

  @override
  void initState() {
    super.initState();
    _state = NetworkingState(widget.service)..addListener(_onState);
    _state.loadDetail(widget.sessionId);
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final session = _state.selected.data;
    return Scaffold(
      appBar: AppBar(title: Text(session?.title ?? 'Networking')),
      body: session == null
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(session.title, style: Theme.of(context).textTheme.headlineSmall),
                  const SizedBox(height: 4),
                  Text('${session.startsAt} • ${session.type}', style: Theme.of(context).textTheme.bodyMedium),
                  const SizedBox(height: 12),
                  const Text('Description'),
                  Text(session.description ?? 'No description'),
                  const SizedBox(height: 16),
                  const Text('What to expect'),
                  const Text('Fast rotations, quick intros, and connection notes.'),
                  const SizedBox(height: 16),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(12),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text('Business card'),
                          const SizedBox(height: 8),
                          LiveEventCard(
                            title: 'Your Name',
                            subtitle: 'Headline',
                            meta: 'Company / Links',
                            onTap: () {},
                            trailing: const Icon(Icons.edit),
                          ),
                          const SizedBox(height: 8),
                          ElevatedButton(
                            onPressed: () async {
                              await _state.register(widget.sessionId);
                              if (mounted) {
                                ScaffoldMessenger.of(context)
                                    .showSnackBar(const SnackBar(content: Text('Registered for session')));
                              }
                            },
                            child: Text(_state.participant == null ? 'Register / Join Waitlist' : 'Registered'),
                          )
                        ],
                      ),
                    ),
                  )
                ],
              ),
            ),
      bottomNavigationBar: session == null
          ? null
          : Padding(
              padding: const EdgeInsets.all(12),
              child: ElevatedButton(
                onPressed: () => Navigator.pushNamed(
                  context,
                  '/live/networking/waiting/${session.id}',
                  arguments: {
                    'title': session.title,
                    'startsAt': session.startsAt,
                    'isLive': session.status == 'in_rotation',
                  },
                ),
                child: const Text('Join Waiting Room'),
              ),
            ),
    );
  }
}
