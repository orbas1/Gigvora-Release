import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/interview.dart';
import '../../services/api_client.dart';
import '../../services/interview_service.dart';
import '../../state/interview_state.dart';

class EmployerInterviewScheduleScreen extends StatelessWidget {
  const EmployerInterviewScheduleScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => InterviewState(
        InterviewService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..load(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Interviews')),
        body: Consumer<InterviewState>(
          builder: (context, state, _) => ListView.builder(
            itemCount: state.interviews.length,
            itemBuilder: (context, index) {
              final invite = state.interviews[index];
              return Card(
                margin: const EdgeInsets.all(8),
                child: ListTile(
                  title: Text(invite.title),
                  subtitle: Text('${invite.candidate.name} • ${invite.datetime}'),
                  trailing: IconButton(
                    icon: const Icon(Icons.edit_calendar),
                    onPressed: () async {
                      final newTime = await _pickDateTime(context);
                      if (newTime != null) state.reschedule(invite.id, newTime.toIso8601String());
                    },
                  ),
                ),
              );
            },
          ),
        ),
        floatingActionButton: FloatingActionButton(
          onPressed: () async {
            final date = await _pickDateTime(context);
            if (date != null) {
              final state = context.read<InterviewState>();
              if (state.interviews.isEmpty) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Load candidates before scheduling a new interview.')),
                );
                return;
              }

              final template = state.interviews.first;
              state.schedule(InterviewInvite(
                id: 0,
                title: 'Interview',
                datetime: date.toIso8601String(),
                candidate: template.candidate,
                jobId: template.jobId,
                status: 'scheduled',
              ));
            }
          },
          child: const Icon(Icons.add),
        ),
      ),
    );
  }

  Future<DateTime?> _pickDateTime(BuildContext context) async {
    final date = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (date == null) return null;
    final time = await showTimePicker(context: context, initialTime: TimeOfDay.now());
    if (time == null) return null;
    return DateTime(date.year, date.month, date.day, time.hour, time.minute);
  }
}
