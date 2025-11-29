import 'dart:async';

import 'package:flutter/material.dart';

class InterviewWaitingRoomScreen extends StatefulWidget {
  const InterviewWaitingRoomScreen({super.key, required this.title});

  final String title;

  @override
  State<InterviewWaitingRoomScreen> createState() => _InterviewWaitingRoomScreenState();
}

class _InterviewWaitingRoomScreenState extends State<InterviewWaitingRoomScreen> {
  Duration remaining = const Duration(minutes: 2);
  late Timer _timer;

  @override
  void initState() {
    super.initState();
    _timer = Timer.periodic(const Duration(seconds: 1), (_) => setState(() => remaining -= const Duration(seconds: 1)));
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final countdown = '${remaining.inMinutes.remainder(60).toString().padLeft(2, '0')}:${(remaining.inSeconds % 60).toString().padLeft(2, '0')}';
    return Scaffold(
      appBar: AppBar(title: const Text('Waiting Room')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(widget.title, style: Theme.of(context).textTheme.headlineSmall),
            const SizedBox(height: 12),
            Text('Starts in $countdown'),
            const SizedBox(height: 12),
            const Text('Tips'),
            const Text('Find a quiet space and test your microphone.'),
            const Spacer(),
            ElevatedButton(
              onPressed: remaining.isNegative ? () => Navigator.pushNamed(context, '/live/interviews/live') : null,
              child: const Text('Enter Interview'),
            )
          ],
        ),
      ),
    );
  }
}
