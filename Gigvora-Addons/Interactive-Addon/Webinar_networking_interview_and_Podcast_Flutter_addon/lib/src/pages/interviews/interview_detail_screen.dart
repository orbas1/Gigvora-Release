import 'package:flutter/material.dart';

import '../../services/interview_service.dart';
import '../../state/interview_state.dart';

class InterviewDetailScreen extends StatefulWidget {
  const InterviewDetailScreen({super.key, required this.service, required this.interviewId});

  final InterviewService service;
  final int interviewId;

  @override
  State<InterviewDetailScreen> createState() => _InterviewDetailScreenState();
}

class _InterviewDetailScreenState extends State<InterviewDetailScreen> {
  late final InterviewState _state;

  @override
  void initState() {
    super.initState();
    _state = InterviewState(widget.service)..addListener(_onState);
    _state.selectInterview(widget.interviewId);
  }

  void _onState() => setState(() {});

  @override
  void dispose() {
    _state.removeListener(_onState);
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final interview = _state.selected.data;
    return Scaffold(
      appBar: AppBar(title: Text(interview?.title ?? 'Interview')),
      body: interview == null
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16),
              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                Text('${interview.title} at ${interview.company}', style: Theme.of(context).textTheme.headlineSmall),
                Text(interview.scheduledAt?.toString() ?? ''),
                const SizedBox(height: 12),
                const Text('Interviewers'),
                Text(interview.interviewers?.join(', ') ?? 'TBD'),
                const SizedBox(height: 12),
                const Text('Instructions'),
                Text(interview.instructions ?? 'Join a few minutes early.'),
                const Spacer(),
                ElevatedButton(
                  onPressed: () => Navigator.pushNamed(context, '/live/interviews/waiting/${interview.id}', arguments: interview.id),
                  child: const Text('Join Waiting Room'),
                ),
              ]),
            ),
    );
  }
}
