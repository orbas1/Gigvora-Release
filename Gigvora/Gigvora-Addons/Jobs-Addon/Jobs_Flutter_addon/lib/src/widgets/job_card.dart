import 'package:flutter/material.dart';

import '../models/job.dart';

class JobCard extends StatelessWidget {
  const JobCard({super.key, required this.job, this.onTap, this.onSave, this.trailing});

  final Job job;
  final VoidCallback? onTap;
  final VoidCallback? onSave;
  final Widget? trailing;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8),
      child: InkWell(
        onTap: onTap,
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const CircleAvatar(radius: 24, child: Icon(Icons.business)),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(job.title, style: Theme.of(context).textTheme.titleMedium),
                    Text(job.company.name, style: Theme.of(context).textTheme.bodySmall),
                    const SizedBox(height: 4),
                    Text('${job.location} · ${job.type}', style: Theme.of(context).textTheme.bodySmall),
                    if (job.salary.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 4),
                        child: Wrap(
                          spacing: 6,
                          children: [
                            Chip(label: Text(job.salary)),
                            ...job.screeningQuestions.map((q) => Chip(label: Text(q.prompt))),
                          ],
                        ),
                      ),
                  ],
                ),
              ),
              Column(
                children: [
                  IconButton(onPressed: onSave, icon: const Icon(Icons.bookmark_border)),
                  if (trailing != null) trailing!,
                ],
              )
            ],
          ),
        ),
      ),
    );
  }
}
