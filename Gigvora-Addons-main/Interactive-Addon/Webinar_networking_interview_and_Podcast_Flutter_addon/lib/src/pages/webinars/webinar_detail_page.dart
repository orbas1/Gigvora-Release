import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/webinar.dart';
import '../../models/webinar_registration.dart';

class WebinarDetailPage extends StatefulWidget {
  final WnipApiClient apiClient;
  final int webinarId;

  const WebinarDetailPage({super.key, required this.apiClient, required this.webinarId});

  @override
  State<WebinarDetailPage> createState() => _WebinarDetailPageState();
}

class _WebinarDetailPageState extends State<WebinarDetailPage> {
  late Future<Webinar> _future;
  WebinarRegistration? registration;
  String? error;
  bool working = false;

  @override
  void initState() {
    super.initState();
    _future = widget.apiClient.fetchWebinarDetails(widget.webinarId);
  }

  Future<void> _register() async {
    setState(() {
      working = true;
      error = null;
    });
    try {
      registration = await widget.apiClient.registerForWebinar(widget.webinarId);
      _future = widget.apiClient.fetchWebinarDetails(widget.webinarId);
    } catch (e) {
      error = e.toString();
    } finally {
      setState(() {
        working = false;
      });
    }
  }

  Future<void> _toggleLive() async {
    setState(() {
      working = true;
      error = null;
    });
    try {
      final updated = await widget.apiClient.toggleWebinarLive(widget.webinarId);
      _future = Future.value(updated);
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
      appBar: AppBar(title: const Text('Webinar Details')),
      body: FutureBuilder<Webinar>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }
          final webinar = snapshot.data;
          if (webinar == null) return const SizedBox.shrink();
          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(webinar.title, style: Theme.of(context).textTheme.headlineSmall),
                const SizedBox(height: 8),
                Text(webinar.description ?? 'No description'),
                const SizedBox(height: 8),
                Text('Starts: ' + webinar.startsAt.toLocal().toString()),
                Text('Ends: ' + webinar.endsAt.toLocal().toString()),
                Text('Status: ' + webinar.status + (webinar.isLive ? ' (Live)' : '')),
                const SizedBox(height: 8),
                if (webinar.isPaid)
                  Text('Ticket price: ' + (webinar.price?.toStringAsFixed(2) ?? 'N/A')),
                if (webinar.waitingRoomMessage != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 8),
                    child: Text('Waiting room: ' + webinar.waitingRoomMessage!),
                  ),
                if (webinar.streamProvider != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 8),
                    child: Text('Stream provider: ' + webinar.streamProvider!),
                  ),
                const SizedBox(height: 16),
                Wrap(
                  spacing: 8,
                  children: [
                    ElevatedButton.icon(
                      icon: const Icon(Icons.login),
                      onPressed: working ? null : _register,
                      label: const Text('Register'),
                    ),
                    ElevatedButton.icon(
                      icon: const Icon(Icons.live_tv),
                      onPressed: working ? null : _toggleLive,
                      label: Text(webinar.isLive ? 'End stream' : 'Go live'),
                    ),
                  ],
                ),
                if (registration != null)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Text('Registration status: ' + registration!.status),
                  ),
                if (webinar.registrations.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Registrations', style: TextStyle(fontWeight: FontWeight.bold)),
                        ...webinar.registrations
                            .map((r) => Text('User ' + r.userId.toString() + ' - ' + r.status))
                            .toList(),
                      ],
                    ),
                  ),
                if (webinar.recordings.isNotEmpty)
                  Padding(
                    padding: const EdgeInsets.only(top: 12),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Recordings', style: TextStyle(fontWeight: FontWeight.bold)),
                        ...webinar.recordings
                            .map((r) => Text(r.path + (r.duration != null ? ' • ' + r.duration.toString() + 's' : '')))
                            .toList(),
                      ],
                    ),
                  ),
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
