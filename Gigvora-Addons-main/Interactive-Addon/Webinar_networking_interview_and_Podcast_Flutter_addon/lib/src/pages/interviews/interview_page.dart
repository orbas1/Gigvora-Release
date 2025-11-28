import 'package:flutter/material.dart';

import '../../api/wnip_api_client.dart';
import '../../models/interview.dart';
import '../../models/interview_score.dart';
import '../../models/interview_slot.dart';
import 'score_form_page.dart';

class InterviewPage extends StatefulWidget {
  final WnipApiClient apiClient;
  final int interviewId;

  const InterviewPage({super.key, required this.apiClient, required this.interviewId});

  @override
  State<InterviewPage> createState() => _InterviewPageState();
}

class _InterviewPageState extends State<InterviewPage> {
  late Future<Interview> _future;
  String? error;

  @override
  void initState() {
    super.initState();
    _future = widget.apiClient.fetchInterviewDetails(widget.interviewId);
  }

  Future<void> _refresh() async {
    setState(() {
      _future = widget.apiClient.fetchInterviewDetails(widget.interviewId);
    });
  }

  Future<void> _scoreSlot(InterviewSlot slot) async {
    final payload = await Navigator.of(context).push<InterviewScorePayload>(
      MaterialPageRoute(
        builder: (_) => ScoreFormPage(slot: slot),
      ),
    );
    if (payload == null) return;
    setState(() {
      error = null;
    });
    try {
      await widget.apiClient.submitInterviewScore(widget.interviewId, slot.id, payload);
      await _refresh();
    } catch (e) {
      setState(() {
        error = e.toString();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Interview')),
      body: FutureBuilder<Interview>(
        future: _future,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text(snapshot.error.toString()));
          }
          final interview = snapshot.data;
          if (interview == null) return const SizedBox.shrink();
          return RefreshIndicator(
            onRefresh: _refresh,
            child: SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(interview.title, style: Theme.of(context).textTheme.headlineSmall),
                  const SizedBox(height: 8),
                  Text(interview.description ?? 'No description'),
                  const SizedBox(height: 8),
                  Text('Scheduled: ' + interview.scheduledAt.toLocal().toString()),
                  Text('Duration: ' + interview.durationMinutes.toString() + ' mins'),
                  Text(interview.isPanel ? 'Panel interview' : 'Single interviewer'),
                  if (interview.location != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 8),
                      child: Text('Location: ' + interview.location!),
                    ),
                  const SizedBox(height: 12),
                  const Text('Slots', style: TextStyle(fontWeight: FontWeight.bold)),
                  ...interview.slots.map(
                    (slot) => Card(
                      child: ListTile(
                        title: Text('Interviewer ' + slot.interviewerId.toString()),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Interviewee ' + slot.intervieweeId.toString()),
                            Text('Starts: ' + slot.startsAt.toLocal().toString()),
                            Text('Ends: ' + slot.endsAt.toLocal().toString()),
                            Text('Status: ' + slot.status),
                            if (slot.meetingLink != null)
                              Text('Meeting: ' + slot.meetingLink!),
                          ],
                        ),
                        trailing: IconButton(
                          icon: const Icon(Icons.rate_review),
                          onPressed: () => _scoreSlot(slot),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  const Text('Scores', style: TextStyle(fontWeight: FontWeight.bold)),
                  ...interview.scores.map(
                    (score) => ListTile(
                      title: Text('Slot ' + score.interviewSlotId.toString()),
                      subtitle: Text('Interviewer ' + score.interviewerId.toString()),
                      trailing: Text(score.scores.join(', ')),
                    ),
                  ),
                  if (error != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 12),
                      child: Text(error!, style: const TextStyle(color: Colors.red)),
                    ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}
