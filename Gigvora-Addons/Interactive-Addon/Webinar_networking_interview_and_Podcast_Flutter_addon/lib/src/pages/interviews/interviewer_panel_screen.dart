import 'package:flutter/material.dart';

import '../../models/interview_score.dart';
import '../../services/interview_service.dart';
import '../../state/interview_state.dart';

class InterviewerPanelScreen extends StatefulWidget {
  const InterviewerPanelScreen({super.key, required this.service, required this.interviewId, required this.slotId});

  final InterviewService service;
  final int interviewId;
  final int slotId;

  @override
  State<InterviewerPanelScreen> createState() => _InterviewerPanelScreenState();
}

class _InterviewerPanelScreenState extends State<InterviewerPanelScreen> {
  late final InterviewState _state;
  final _formKey = GlobalKey<FormState>();
  final Map<String, double> _scores = {'Communication': 3, 'Problem Solving': 3, 'Collaboration': 3};
  final Map<String, String> _comments = {};
  bool locked = false;

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

  Future<void> _save() async {
    if (locked) return;
    await _state.submitScore(
      widget.interviewId,
      widget.slotId,
      InterviewScorePayload(criteria: _scores, comments: _comments, recommendation: 'hire'),
    );
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Scores saved')));
    }
  }

  @override
  Widget build(BuildContext context) {
    final interview = _state.selected.data;
    return Scaffold(
      appBar: AppBar(title: Text(interview?.title ?? 'Interviewer Panel')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(interview?.title ?? '', style: Theme.of(context).textTheme.headlineSmall),
              Text(interview?.scheduledAt?.toString() ?? ''),
              const SizedBox(height: 12),
              const Text('Criteria & Scoring'),
              Expanded(
                child: ListView(
                  children: _scores.keys
                      .map((key) => ListTile(
                            title: Text(key),
                            subtitle: Slider(
                              value: _scores[key]!,
                              min: 1,
                              max: 5,
                              divisions: 4,
                              label: _scores[key]!.toStringAsFixed(0),
                              onChanged: locked ? null : (value) => setState(() => _scores[key] = value),
                            ),
                            trailing: SizedBox(
                              width: 140,
                              child: TextFormField(
                                enabled: !locked,
                                decoration: const InputDecoration(hintText: 'Comments'),
                                onChanged: (value) => _comments[key] = value,
                              ),
                            ),
                          ))
                      .toList(),
                ),
              ),
              Row(
                children: [
                  OutlinedButton(
                    onPressed: locked
                        ? null
                        : () => setState(() {
                              locked = true;
                            }),
                    child: const Text('Lock scores'),
                  ),
                  const SizedBox(width: 8),
                  ElevatedButton(onPressed: locked ? null : _save, child: const Text('Save')),
                ],
              )
            ],
          ),
        ),
      ),
    );
  }
}
