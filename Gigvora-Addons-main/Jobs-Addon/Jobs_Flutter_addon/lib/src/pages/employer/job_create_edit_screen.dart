import 'package:flutter/material.dart';

class JobCreateEditScreen extends StatefulWidget {
  const JobCreateEditScreen({super.key});

  @override
  State<JobCreateEditScreen> createState() => _JobCreateEditScreenState();
}

class _JobCreateEditScreenState extends State<JobCreateEditScreen> {
  int step = 0;
  final Map<String, dynamic> _payload = {};

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Post a Job')),
      body: Stepper(
        currentStep: step,
        onStepContinue: () {
          if (step < 4) {
            setState(() => step += 1);
          } else {
            Navigator.pop(context, _payload);
          }
        },
        onStepCancel: () => setState(() => step = step == 0 ? 0 : step - 1),
        steps: [
          Step(
            title: const Text('Basics'),
            content: Column(
              children: [
                TextField(decoration: const InputDecoration(labelText: 'Job title'), onChanged: (v) => _payload['title'] = v),
                TextField(decoration: const InputDecoration(labelText: 'Location'), onChanged: (v) => _payload['location'] = v),
              ],
            ),
          ),
          Step(
            title: const Text('Details'),
            content: TextField(
              decoration: const InputDecoration(labelText: 'Description'),
              maxLines: 4,
              onChanged: (v) => _payload['description'] = v,
            ),
          ),
          Step(
            title: const Text('Compensation'),
            content: TextField(
              decoration: const InputDecoration(labelText: 'Salary range'),
              onChanged: (v) => _payload['salary'] = v,
            ),
          ),
          Step(
            title: const Text('Screening'),
            content: const Text('Attach screening questions or templates'),
          ),
          Step(
            title: const Text('Publish'),
            content: Column(
              children: const [
                Text('Set visibility and go live'),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
