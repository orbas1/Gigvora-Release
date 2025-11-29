import 'package:flutter/material.dart';

import '../models/candidate.dart';

class CandidateCard extends StatelessWidget {
  const CandidateCard({super.key, required this.candidate, this.onTap, this.onMove});

  final CandidateProfile candidate;
  final VoidCallback? onTap;
  final VoidCallback? onMove;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        onTap: onTap,
        leading: const CircleAvatar(child: Icon(Icons.person)),
        title: Text(candidate.name),
        subtitle: Text(candidate.title),
        trailing: IconButton(onPressed: onMove, icon: const Icon(Icons.chevron_right)),
      ),
    );
  }
}
