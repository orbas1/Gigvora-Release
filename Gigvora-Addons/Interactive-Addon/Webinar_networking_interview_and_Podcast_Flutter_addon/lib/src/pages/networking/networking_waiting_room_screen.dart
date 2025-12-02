import 'dart:async';

import 'package:flutter/material.dart';

class NetworkingWaitingRoomScreen extends StatefulWidget {
  const NetworkingWaitingRoomScreen({
    super.key,
    required this.sessionTitle,
    required this.startsAt,
    this.isLive = false,
  });

  final String sessionTitle;
  final DateTime startsAt;
  final bool isLive;

  @override
  State<NetworkingWaitingRoomScreen> createState() => _NetworkingWaitingRoomScreenState();
}

class _NetworkingWaitingRoomScreenState extends State<NetworkingWaitingRoomScreen> {
  late Duration remaining;
  late Timer _timer;
  bool live = false;
  final TextEditingController _headlineController = TextEditingController();
  final TextEditingController _roleController = TextEditingController();
  final TextEditingController _companyController = TextEditingController();
  final TextEditingController _linksController = TextEditingController();
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    live = widget.isLive;
    remaining = widget.startsAt.difference(DateTime.now());
    _timer = Timer.periodic(const Duration(seconds: 1), (_) {
      setState(() {
        remaining = widget.startsAt.difference(DateTime.now());
        if (remaining.inSeconds <= 0) live = true;
      });
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    _headlineController.dispose();
    _roleController.dispose();
    _companyController.dispose();
    _linksController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final countdown = remaining.inSeconds < 0
        ? '00:00'
        : '${remaining.inMinutes.remainder(60).toString().padLeft(2, '0')}:${(remaining.inSeconds % 60).toString().padLeft(2, '0')}';
    return Scaffold(
      appBar: AppBar(title: const Text('Waiting Room')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(widget.sessionTitle, style: Theme.of(context).textTheme.headlineSmall),
            const SizedBox(height: 12),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(12),
                child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                  const Text('Countdown'),
                  Text(countdown, style: Theme.of(context).textTheme.displaySmall),
                  const SizedBox(height: 12),
                  const Text('Edit card'),
                  TextField(controller: _headlineController, decoration: const InputDecoration(labelText: 'Headline')),
                  TextField(controller: _roleController, decoration: const InputDecoration(labelText: 'Role')),
                  TextField(controller: _companyController, decoration: const InputDecoration(labelText: 'Company')),
                  TextField(controller: _linksController, decoration: const InputDecoration(labelText: 'Links')),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      ElevatedButton(
                        onPressed: _saving
                            ? null
                            : () {
                                setState(() => _saving = true);
                                Future.delayed(const Duration(milliseconds: 500), () {
                                  if (mounted) setState(() => _saving = false);
                                });
                              },
                        child: Text(_saving ? 'Saving…' : 'Save Card'),
                      ),
                      const SizedBox(width: 12),
                      const Text('Shared with partners once live'),
                    ],
                  ),
                ]),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: live ? () => Navigator.pushNamed(context, '/live/networking/live') : null,
              child: const Text('Join Session'),
            )
          ],
        ),
      ),
    );
  }
}
