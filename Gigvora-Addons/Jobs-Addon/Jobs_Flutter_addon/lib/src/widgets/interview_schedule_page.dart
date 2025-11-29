import 'package:flutter/material.dart';

import '../data/jobs_repository.dart';

class InterviewSchedulePage extends StatefulWidget {
  const InterviewSchedulePage({
    super.key,
    required this.repository,
    required this.applicationId,
  });

  final JobsRepository repository;
  final int applicationId;

  @override
  State<InterviewSchedulePage> createState() => _InterviewSchedulePageState();
}

class _InterviewSchedulePageState extends State<InterviewSchedulePage> {
  final _startController = TextEditingController();
  final _endController = TextEditingController();
  final _locationController = TextEditingController();
  final _linkController = TextEditingController();
  String? _message;
  bool _saving = false;

  @override
  void dispose() {
    _startController.dispose();
    _endController.dispose();
    _locationController.dispose();
    _linkController.dispose();
    super.dispose();
  }

  Future<void> _schedule() async {
    setState(() {
      _saving = true;
      _message = null;
    });
    try {
      final start = DateTime.parse(_startController.text);
      final end = DateTime.parse(_endController.text);
      final schedule = await widget.repository.scheduleInterview(
        applicationId: widget.applicationId,
        start: start,
        end: end,
        location: _locationController.text.isEmpty ? null : _locationController.text,
        link: _linkController.text.isEmpty ? null : _linkController.text,
      );
      setState(() => _message = 'Scheduled from ${schedule.start} to ${schedule.end}');
    } catch (e) {
      setState(() => _message = e.toString());
    } finally {
      setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Schedule interview')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _startController,
              decoration: const InputDecoration(
                labelText: 'Start (ISO 8601)',
                hintText: '2024-12-31T10:00:00Z',
              ),
            ),
            TextField(
              controller: _endController,
              decoration: const InputDecoration(
                labelText: 'End (ISO 8601)',
                hintText: '2024-12-31T11:00:00Z',
              ),
            ),
            TextField(
              controller: _locationController,
              decoration: const InputDecoration(labelText: 'Location (optional)'),
            ),
            TextField(
              controller: _linkController,
              decoration: const InputDecoration(labelText: 'Meeting link (optional)'),
            ),
            const SizedBox(height: 12),
            if (_message != null)
              Align(
                alignment: Alignment.centerLeft,
                child: Text(
                  _message!,
                  style: TextStyle(
                    color: _message!.startsWith('Scheduled') ? Colors.green : Colors.red,
                  ),
                ),
              ),
            const SizedBox(height: 12),
            FilledButton(
              onPressed: _saving ? null : _schedule,
              child: _saving
                  ? const SizedBox(
                      height: 16,
                      width: 16,
                      child: CircularProgressIndicator(strokeWidth: 2),
                    )
                  : const Text('Schedule'),
            ),
          ],
        ),
      ),
    );
  }
}
