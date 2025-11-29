import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../models/job.dart';
import '../../services/api_client.dart';
import '../../services/applications_service.dart';
import '../../state/applications_state.dart';
import '../../widgets/application_timeline.dart';

class JobApplyScreen extends StatefulWidget {
  const JobApplyScreen({super.key, required this.job});

  final Job job;

  @override
  State<JobApplyScreen> createState() => _JobApplyScreenState();
}

class _JobApplyScreenState extends State<JobApplyScreen> {
  int step = 0;
  final _formKey = GlobalKey<FormState>();
  final Map<String, dynamic> _payload = {};

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => ApplicationsState(
        ApplicationsService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      ),
      child: Scaffold(
        appBar: AppBar(title: Text('Apply to ${widget.job.title}')),
        body: Form(
          key: _formKey,
          child: Stepper(
            currentStep: step,
            onStepContinue: () {
              if (step < 4) {
                setState(() => step += 1);
              } else {
                _submit(context);
              }
            },
            onStepCancel: () => setState(() => step = step == 0 ? 0 : step - 1),
            controlsBuilder: (context, details) => Row(
              children: [
                ElevatedButton(onPressed: details.onStepContinue, child: Text(step == 4 ? 'Submit' : 'Next')),
                const SizedBox(width: 8),
                TextButton(onPressed: details.onStepCancel, child: const Text('Back')),
              ],
            ),
            steps: [
              Step(
                title: const Text('Profile'),
                content: Column(
                  children: [
                    TextFormField(decoration: const InputDecoration(labelText: 'Name'), onSaved: (v) => _payload['name'] = v),
                    TextFormField(decoration: const InputDecoration(labelText: 'Email'), onSaved: (v) => _payload['email'] = v),
                    TextFormField(decoration: const InputDecoration(labelText: 'Phone'), onSaved: (v) => _payload['phone'] = v),
                  ],
                ),
              ),
              Step(
                title: const Text('CV'),
                content: Column(children: const [Text('Select existing CV or upload')]),
              ),
              Step(
                title: const Text('Cover letter'),
                content: TextFormField(
                  maxLines: 4,
                  decoration: const InputDecoration(labelText: 'Cover letter'),
                  onSaved: (v) => _payload['cover_letter'] = v,
                ),
              ),
              Step(
                title: const Text('Screening'),
                content: Column(
                  children: widget.job.screeningQuestions
                      .map((q) => TextFormField(
                            decoration: InputDecoration(labelText: q.prompt),
                            onSaved: (v) => _payload['screening_${q.id}'] = v,
                          ))
                      .toList(),
                ),
              ),
              Step(
                title: const Text('Review'),
                content: const ApplicationTimeline(events: [
                  TimelineEvent(label: 'Ready to submit', subtitle: 'Review your details'),
                ]),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _submit(BuildContext context) async {
    _formKey.currentState?.save();
    final state = context.read<ApplicationsState>();
    await state.submit({..._payload, 'job_id': widget.job.id});
    if (mounted) Navigator.pop(context);
  }
}
