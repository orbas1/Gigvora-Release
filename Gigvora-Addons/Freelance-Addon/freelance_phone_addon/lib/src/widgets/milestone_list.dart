import 'package:flutter/material.dart';
import '../models/milestone.dart';

class MilestoneList extends StatelessWidget {
  const MilestoneList({super.key, required this.milestones, this.onAction});
  final List<Milestone> milestones;
  final void Function(Milestone, String)? onAction;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: milestones
          .map((m) => Card(
                child: ListTile(
                  title: Text(m.title),
                  subtitle: Text('${m.status} · Due ${m.dueDate}'),
                  trailing: Text('\$${m.amount.toStringAsFixed(0)}'),
                  onTap: onAction == null
                      ? null
                      : () => onAction!(m, m.status == 'funded' ? 'submit' : 'release'),
                ),
              ))
          .toList(),
    );
  }
}
