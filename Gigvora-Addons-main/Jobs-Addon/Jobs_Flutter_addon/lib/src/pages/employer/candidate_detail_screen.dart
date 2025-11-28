import 'package:flutter/material.dart';

import '../../models/candidate.dart';

class CandidateDetailScreen extends StatelessWidget {
  const CandidateDetailScreen({super.key, required this.candidate});

  final CandidateProfile candidate;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Candidate')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ListTile(
              leading: const CircleAvatar(child: Icon(Icons.person)),
              title: Text(candidate.name),
              subtitle: Text(candidate.title),
            ),
            Text('Application details', style: Theme.of(context).textTheme.titleMedium),
            Text('Stage: ${candidate.stage ?? 'applied'}'),
            const SizedBox(height: 12),
            Text('Notes', style: Theme.of(context).textTheme.titleMedium),
            TextField(maxLines: 3, decoration: const InputDecoration(hintText: 'Add note')),
            const Spacer(),
            Row(
              children: [
                Expanded(child: ElevatedButton(onPressed: () {}, child: const Text('Invite to interview'))),
                const SizedBox(width: 8),
                Expanded(child: OutlinedButton(onPressed: () {}, child: const Text('Reject'))),
              ],
            )
          ],
        ),
      ),
    );
  }
}
