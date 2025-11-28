import 'package:flutter/material.dart';
import '../../../widgets/milestone_list.dart';
import '../../../models/milestone.dart';
import '../../ui/theme/mobile_theme.dart';

class GigOrderDetailScreen extends StatelessWidget {
  const GigOrderDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final milestones = [
      Milestone(title: 'Draft delivery', amount: 60, dueDate: 'in 2 days', status: 'funded'),
      Milestone(title: 'Final files', amount: 60, dueDate: 'in 5 days', status: 'pending'),
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('Order Detail')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          const ListTile(title: Text('Logo design for Alice'), subtitle: Text('Due in 5 days')),
          const Divider(),
          const Text('Requirements'),
          Text('Provide two logo options and source files.',
              style: TextStyle(color: FreelanceMobileTheme.mutedText(context))),
          const SizedBox(height: 12),
          MilestoneList(milestones: milestones, onAction: (m, action) {}),
          const SizedBox(height: 12),
          ElevatedButton(onPressed: () {}, child: const Text('Deliver Work')),
          TextButton(onPressed: () {}, child: const Text('Open Dispute')),
        ],
      ),
    );
  }
}
