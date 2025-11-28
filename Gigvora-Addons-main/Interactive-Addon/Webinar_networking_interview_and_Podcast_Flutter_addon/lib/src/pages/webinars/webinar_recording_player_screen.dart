import 'package:flutter/material.dart';

import '../../models/recording.dart';

class WebinarRecordingPlayerScreen extends StatefulWidget {
  const WebinarRecordingPlayerScreen({super.key, required this.recording});

  final Recording recording;

  @override
  State<WebinarRecordingPlayerScreen> createState() => _WebinarRecordingPlayerScreenState();
}

class _WebinarRecordingPlayerScreenState extends State<WebinarRecordingPlayerScreen> {
  double position = 0;
  double speed = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.recording.title ?? 'Recording')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              height: 200,
              decoration: BoxDecoration(color: Colors.black87, borderRadius: BorderRadius.circular(12)),
              child: const Center(child: Icon(Icons.play_circle_outline, color: Colors.white, size: 48)),
            ),
            const SizedBox(height: 16),
            Slider(
              value: position,
              onChanged: (value) => setState(() => position = value),
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('Speed ${speed.toStringAsFixed(2)}x'),
                DropdownButton<double>(
                  value: speed,
                  onChanged: (value) => setState(() => speed = value ?? 1),
                  items: const [
                    DropdownMenuItem(value: 0.75, child: Text('0.75x')),
                    DropdownMenuItem(value: 1, child: Text('1x')),
                    DropdownMenuItem(value: 1.25, child: Text('1.25x')),
                    DropdownMenuItem(value: 1.5, child: Text('1.5x')),
                  ],
                )
              ],
            ),
            const SizedBox(height: 16),
            Text('Chapters', style: Theme.of(context).textTheme.titleMedium),
            Wrap(spacing: 8, children: [
              ActionChip(label: const Text('Intro'), onPressed: () => setState(() => position = 0.1)),
              ActionChip(label: const Text('Demo'), onPressed: () => setState(() => position = 0.4)),
            ]),
            const SizedBox(height: 16),
            Text('Resources', style: Theme.of(context).textTheme.titleMedium),
            const Text('Slides and notes will appear here.'),
          ],
        ),
      ),
    );
  }
}
