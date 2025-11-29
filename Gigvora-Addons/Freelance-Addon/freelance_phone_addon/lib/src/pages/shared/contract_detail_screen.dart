import 'package:flutter/material.dart';
import '../../models/contract.dart';
import '../../widgets/milestone_list.dart';

class ContractDetailScreen extends StatelessWidget {
  const ContractDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final contract = (ModalRoute.of(context)?.settings.arguments as Contract?) ??
        Contract(id: 0, title: 'Contract', amount: 0, status: 'Active', counterpart: 'User', milestones: const []);
    return Scaffold(
      appBar: AppBar(title: Text(contract.title)),
      bottomNavigationBar: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              OutlinedButton(onPressed: () {}, child: const Text('Open Dispute')),
              const SizedBox(width: 8),
              Expanded(child: ElevatedButton(onPressed: () {}, child: const Text('Request Release'))),
            ],
          ),
        ),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Text('Counterpart: ${contract.counterpart}'),
          Text('Amount: \$${contract.amount}'),
          const SizedBox(height: 12),
          MilestoneList(milestones: contract.milestones, onAction: (m, action) {}),
          const Divider(),
          const Text('Messages'),
          const ListTile(title: Text('No messages yet')),
        ],
      ),
    );
  }
}
