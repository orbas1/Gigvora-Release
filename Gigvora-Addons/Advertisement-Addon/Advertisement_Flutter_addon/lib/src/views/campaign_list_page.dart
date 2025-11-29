import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../state/ads_blocs.dart';

class CampaignListPage extends StatelessWidget {
  const CampaignListPage({super.key});

  static Widget builder(BuildContext context) => const CampaignListPage();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Campaigns')),
      body: BlocBuilder<CampaignBloc, CampaignState>(
        builder: (context, state) {
          if (state.status == CampaignStatus.loading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (state.status == CampaignStatus.error) {
            return Center(child: Text(state.error ?? 'Error'));
          }
          return ListView.builder(
            itemCount: state.campaigns.length,
            itemBuilder: (context, index) {
              final campaign = state.campaigns[index];
              return Card(
                child: ListTile(
                  leading: const Icon(Icons.campaign),
                  title: Text(campaign.name),
                  subtitle: Text('${campaign.objective} · ${campaign.status}'),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text('Daily: \\$${campaign.dailyBudget.toStringAsFixed(2)}'),
                      Text('Lifetime: \\$${campaign.lifetimeBudget.toStringAsFixed(2)}'),
                    ],
                  ),
                  onTap: () => Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => CampaignEditorPage(campaign: campaign)),
                  ),
                ),
              );
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => Navigator.of(context).push(
          MaterialPageRoute(builder: (_) => CampaignEditorPage(campaign: newCampaignTemplate())),
        ),
        child: const Icon(Icons.add),
      ),
    );
  }
}

class CampaignEditorPage extends StatefulWidget {
  const CampaignEditorPage({super.key, required this.campaign});

  final Campaign campaign;

  @override
  State<CampaignEditorPage> createState() => _CampaignEditorPageState();
}

class _CampaignEditorPageState extends State<CampaignEditorPage> {
  late final TextEditingController _name;
  late final TextEditingController _objective;
  late final TextEditingController _dailyBudget;
  late final TextEditingController _lifetimeBudget;

  @override
  void initState() {
    super.initState();
    _name = TextEditingController(text: widget.campaign.name);
    _objective = TextEditingController(text: widget.campaign.objective);
    _dailyBudget = TextEditingController(text: widget.campaign.dailyBudget.toString());
    _lifetimeBudget = TextEditingController(text: widget.campaign.lifetimeBudget.toString());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Campaign')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(controller: _name, decoration: const InputDecoration(labelText: 'Name')),
            TextField(controller: _objective, decoration: const InputDecoration(labelText: 'Objective')),
            TextField(controller: _dailyBudget, decoration: const InputDecoration(labelText: 'Daily Budget'), keyboardType: TextInputType.number),
            TextField(controller: _lifetimeBudget, decoration: const InputDecoration(labelText: 'Lifetime Budget'), keyboardType: TextInputType.number),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () async {
                final bloc = context.read<CampaignBloc>();
                final updated = widget.campaign.copyWith(
                  name: _name.text,
                  objective: _objective.text,
                  dailyBudget: double.tryParse(_dailyBudget.text) ?? widget.campaign.dailyBudget,
                  lifetimeBudget: double.tryParse(_lifetimeBudget.text) ?? widget.campaign.lifetimeBudget,
                );
                await bloc.save(updated);
                if (mounted) Navigator.of(context).pop();
              },
              child: const Text('Save'),
            ),
          ],
        ),
      ),
    );
  }
}

Campaign newCampaignTemplate() => Campaign(
      id: 0,
      advertiserId: 0,
      name: '',
      objective: '',
      status: 'draft',
      dailyBudget: 0,
      lifetimeBudget: 0,
      startDate: DateTime.now(),
      endDate: DateTime.now().add(const Duration(days: 7)),
    );
