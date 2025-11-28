import 'package:flutter/material.dart';

class PodcastLiveRecordingScreen extends StatefulWidget {
  const PodcastLiveRecordingScreen({super.key});

  @override
  State<PodcastLiveRecordingScreen> createState() => _PodcastLiveRecordingScreenState();
}

class _PodcastLiveRecordingScreenState extends State<PodcastLiveRecordingScreen> {
  bool recording = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Live Podcast Recording')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              height: 200,
              decoration: BoxDecoration(color: Colors.black87, borderRadius: BorderRadius.circular(12)),
              child: const Center(child: Icon(Icons.mic, color: Colors.white, size: 48)),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () => setState(() => recording = !recording),
              child: Text(recording ? 'Stop Recording' : 'Record'),
            ),
            const SizedBox(height: 12),
            const Text('Guests'),
            Expanded(
              child: ListView.builder(
                itemCount: 3,
                itemBuilder: (context, index) => SwitchListTile(
                  value: true,
                  onChanged: (_) {},
                  title: Text('Guest ${index + 1}'),
                  subtitle: const Text('Mute/unmute'),
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
