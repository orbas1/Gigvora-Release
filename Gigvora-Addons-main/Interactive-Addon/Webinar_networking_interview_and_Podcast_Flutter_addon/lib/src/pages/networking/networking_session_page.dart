import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/networking_session.dart';
import '../../models/networking_participant.dart';

class NetworkingSessionPage extends StatefulWidget {
  final WnipApiClient apiClient;
  final int sessionId;

  const NetworkingSessionPage({super.key, required this.apiClient, required this.sessionId});

  @override
  State<NetworkingSessionPage> createState() => _NetworkingSessionPageState();
}

class _NetworkingSessionPageState extends State<NetworkingSessionPage> {
  late Future<NetworkingSession> _future;
  NetworkingParticipant? participant;
  String? error;
  bool working = false;

  @override
  void initState() {
    super.initState();
    _future = widget.apiClient.fetchNetworkingSession(widget.sessionId);
  }

  Future<void> _register() async {
    setState(() {
      working = true;
      error = null;
    });
    try {
      participant = await widget.apiClient.registerForNetworking(widget.sessionId);
      _future = widget.apiClient.fetchNetworkingSession(widget.sessionId);
    } catch (e) {
      error = e.toString();
    } finally {
      setState(() {
        working = false;
      });
    }
  }

  Future<void> _rotate() async {
    setState(() {
      working = true;
      error = null;
    });
    try {
      await widget.apiClient.rotateNetworking(widget.sessionId);
      _future = widget.apiClient.fetchNetworkingSession(widget.sessionId);
    } catch (e) {
      error = e.toString();
    } finally {
      setState(() {
        working = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Networking Session')),
      body: FutureBuilder<NetworkingSession>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }
          final session = snapshot.data;
          if (session == null) return const SizedBox.shrink();
          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(session.title, style: Theme.of(context).textTheme.headlineSmall),
                const SizedBox(height: 8),
                Text(session.description ?? 'No description'),
                const SizedBox(height: 8),
                Text('Starts: ' + session.startsAt.toLocal().toString()),
                Text('Duration: ' + session.durationSeconds.toString() + 's'),
                Text('Rotation: every ' + session.rotationInterval.toString() + 's'),
                Text('Status: ' + session.status),
                const SizedBox(height: 12),
                Wrap(
                  spacing: 8,
                  children: [
                    ElevatedButton.icon(
                      onPressed: working ? null : _register,
                      icon: const Icon(Icons.login),
                      label: const Text('Register'),
                    ),
                    ElevatedButton.icon(
                      onPressed: working ? null : _rotate,
                      icon: const Icon(Icons.rotate_right),
                      label: const Text('Rotate'),
                    ),
                  ],
                ),
                if (participant != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Text('You are registered at position ' + participant!.rotationPosition.toString()),
                  ),
                const SizedBox(height: 12),
                const Text('Participants', style: TextStyle(fontWeight: FontWeight.bold)),
                ...session.participants
                    .map((p) => ListTile(
                          contentPadding: EdgeInsets.zero,
                          title: Text('User ' + p.userId.toString()),
                          subtitle: Text('Position ' + p.rotationPosition.toString()),
                          trailing: p.currentPartnerId != null
                              ? Text('Partner ' + p.currentPartnerId.toString())
                              : null,
                        ))
                    .toList(),
                if (error != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Text(error!, style: const TextStyle(color: Colors.red)),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }
}
