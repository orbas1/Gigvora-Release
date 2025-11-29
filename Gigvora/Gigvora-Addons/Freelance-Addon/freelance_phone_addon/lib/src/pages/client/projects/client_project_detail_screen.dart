import 'package:flutter/material.dart';
import '../../../models/project.dart';

class ClientProjectDetailScreen extends StatelessWidget {
  const ClientProjectDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final project = (ModalRoute.of(context)?.settings.arguments as Project?) ??
        Project(id: 0, title: 'Project', description: '', budget: 0, type: 'fixed', proposalsCount: 0);
    final tabs = ['Proposals', 'Overview', 'Activity'];
    return DefaultTabController(
      length: tabs.length,
      child: Scaffold(
        appBar: AppBar(title: Text(project.title), bottom: TabBar(tabs: tabs.map((t) => Tab(text: t)).toList())),
        body: TabBarView(children: [
          _proposalsTab(),
          Padding(padding: const EdgeInsets.all(16), child: Text(project.description.isEmpty ? 'No description' : project.description)),
          Padding(padding: const EdgeInsets.all(16), child: Column(children: const [ListTile(title: Text('Project posted')), ListTile(title: Text('Proposal received'))])),
        ]),
      ),
    );
  }

  Widget _proposalsTab() {
    final proposals = [
      {'freelancer': 'Sam', 'bid': 1200, 'time': '10 days'},
    ];
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: proposals.length,
      itemBuilder: (context, index) {
        final p = proposals[index];
        return Card(
          child: Padding(
            padding: const EdgeInsets.all(12),
            child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
              Text(p['freelancer'] as String, style: const TextStyle(fontWeight: FontWeight.bold)),
              Text('Bid: \$${p['bid']} • ${p['time']}'),
              const SizedBox(height: 8),
              Wrap(spacing: 8, children: [
                OutlinedButton(onPressed: () {}, child: const Text('Shortlist')),
                OutlinedButton(onPressed: () {}, child: const Text('Reject')),
                ElevatedButton(onPressed: () {}, child: const Text('Hire')),
              ]),
            ]),
          ),
        );
      },
    );
  }
}
