import 'dart:async';

import 'package:flutter/material.dart';

class NetworkingLiveScreen extends StatefulWidget {
  const NetworkingLiveScreen({super.key});

  @override
  State<NetworkingLiveScreen> createState() => _NetworkingLiveScreenState();
}

class _NetworkingLiveScreenState extends State<NetworkingLiveScreen> {
  Duration remaining = const Duration(minutes: 2);
  int round = 1;
  late Timer _timer;

  @override
  void initState() {
    super.initState();
    _timer = Timer.periodic(const Duration(seconds: 1), (_) {
      setState(() {
        remaining -= const Duration(seconds: 1);
        if (remaining.inSeconds <= 0) {
          round += 1;
          remaining = const Duration(minutes: 2);
        }
      });
    });
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
      appBar: AppBar(title: Text('Round $round of 6')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text('Current partner'),
                Text('Time left: $countdown', style: Theme.of(context).textTheme.titleMedium),
              ],
            ),
            const SizedBox(height: 12),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(color: Colors.grey.shade100, borderRadius: BorderRadius.circular(12)),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: const [
                Text('Jamie Doe', style: TextStyle(fontWeight: FontWeight.bold)),
                Text('Role: Growth Lead'),
                Text('Links: LinkedIn, Site'),
              ]),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Notes'),
              maxLines: 4,
              onChanged: (value) => {},
            ),
            const Spacer(),
            Row(
              children: [
                OutlinedButton(onPressed: () {}, child: const Text('Next')),
                const SizedBox(width: 8),
                OutlinedButton(onPressed: () {}, child: const Text('Report')),
                const Spacer(),
                ElevatedButton(onPressed: () => Navigator.pop(context), child: const Text('Leave')),
              ],
            )
          ],
        ),
      ),
    );
  }
}
