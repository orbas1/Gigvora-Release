import 'dart:async';

import 'package:flutter/material.dart';

import '../../theme/live_mobile_theme.dart';

class NetworkingLiveScreen extends StatefulWidget {
  const NetworkingLiveScreen({super.key});

  @override
  State<NetworkingLiveScreen> createState() => _NetworkingLiveScreenState();
}

class _NetworkingLiveScreenState extends State<NetworkingLiveScreen> {
  Duration remaining = const Duration(minutes: 2);
  int round = 1;
  late Timer _timer;
  final Map<int, String> _notes = {};
  bool starred = false;

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
                Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  Text('Round $round of 6', style: Theme.of(context).textTheme.titleMedium),
                  const Text('Rotations auto-advance when timer ends'),
                ]),
                Text('Time left: $countdown', style: Theme.of(context).textTheme.titleMedium),
              ],
            ),
            const SizedBox(height: 12),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                  color: LiveMobileTheme.surfaceVariant(context),
                  borderRadius: BorderRadius.circular(LiveMobileTheme.cardRadius)),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                const Text('Jamie Doe', style: TextStyle(fontWeight: FontWeight.bold)),
                const Text('Role: Growth Lead'),
                const Text('Links: LinkedIn, Site'),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 8,
                  children: [
                    FilterChip(
                      label: const Text('Star contact'),
                      selected: starred,
                      onSelected: (value) => setState(() => starred = value),
                    ),
                    ActionChip(
                      label: const Text('Exchange contact'),
                      onPressed: () {
                        ScaffoldMessenger.of(context)
                            .showSnackBar(const SnackBar(content: Text('Contact exchange requested')));
                      },
                    ),
                  ],
                )
              ]),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Notes'),
              maxLines: 4,
              onChanged: (value) => setState(() => _notes[round] = value),
            ),
            const Spacer(),
            Row(
              children: [
                OutlinedButton(
                    onPressed: () {
                      setState(() {
                        round += 1;
                        remaining = const Duration(minutes: 2);
                      });
                    },
                    child: const Text('Next')),
                const SizedBox(width: 8),
                OutlinedButton(
                    onPressed: () {
                      ScaffoldMessenger.of(context)
                          .showSnackBar(const SnackBar(content: Text('Report submitted to moderators')));
                    },
                    child: const Text('Report')),
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
