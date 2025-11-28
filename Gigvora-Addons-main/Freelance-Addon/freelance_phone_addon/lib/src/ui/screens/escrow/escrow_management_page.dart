import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../state/escrow_management_provider.dart';
import '../../../state/escrow_provider.dart';
import '../../../repositories/freelance_repository.dart';

class EscrowManagementPage extends ConsumerStatefulWidget {
  const EscrowManagementPage({super.key});

  static const routeName = '/freelance/escrow/manage';

  @override
  ConsumerState<EscrowManagementPage> createState() => _EscrowManagementPageState();
}

class _EscrowManagementPageState extends ConsumerState<EscrowManagementPage> {
  final partialController = TextEditingController();
  final escrowIdController = TextEditingController();
  final decisionController = TextEditingController();
  final adminController = TextEditingController();

  @override
  void dispose() {
    partialController.dispose();
    escrowIdController.dispose();
    decisionController.dispose();
    adminController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final escrows = ref.watch(escrowsProvider);
    final actions = ref.watch(escrowActionsProvider);
    return Scaffold(
      appBar: AppBar(title: const Text('Escrow management')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          escrows.when(
            data: (data) => Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Escrows (${data.length})', style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 8),
                ...data.map((e) => ListTile(
                      leading: const Icon(Icons.safety_check_outlined),
                      title: Text('Escrow #${e.id}'),
                      subtitle: Text('Status: ${e.status} • Released ${e.releasedAmount}'),
                      trailing: Text(e.currency),
                    )),
              ],
            ),
            loading: () => const Center(child: CircularProgressIndicator()),
            error: (err, _) => Text('Escrows failed: $err'),
          ),
          const Divider(height: 32),
          actions.when(
            data: (data) => Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Actions (${data.length})', style: Theme.of(context).textTheme.titleMedium),
                ...data.map((a) => ListTile(
                      leading: const Icon(Icons.history_outlined),
                      title: Text(a.type),
                      subtitle: Text(a.notes ?? ''),
                      trailing: Text(a.amount?.toStringAsFixed(2) ?? ''),
                    )),
              ],
            ),
            loading: () => const Center(child: CircularProgressIndicator()),
            error: (err, _) => Text('Actions failed: $err'),
          ),
          const Divider(height: 32),
          Text('Partial release', style: Theme.of(context).textTheme.titleMedium),
          TextField(
            controller: escrowIdController,
            decoration: const InputDecoration(labelText: 'Escrow ID'),
            keyboardType: TextInputType.number,
          ),
          TextField(
            controller: partialController,
            decoration: const InputDecoration(labelText: 'Amount'),
            keyboardType: TextInputType.numberWithOptions(decimal: true),
          ),
          TextField(
            controller: adminController,
            decoration: const InputDecoration(labelText: 'Released by'),
          ),
          const SizedBox(height: 8),
          ElevatedButton(
            onPressed: () async {
              final escrowId = int.tryParse(escrowIdController.text);
              final amount = double.tryParse(partialController.text);
              final actor = adminController.text;
              if (escrowId == null || amount == null || actor.isEmpty) return;
              await ref.read(freelanceRepositoryProvider).partialRelease(
                    escrowId: escrowId,
                    amount: amount,
                    releasedBy: actor,
                  );
              ref.invalidate(escrowActionsProvider);
              ref.invalidate(escrowsProvider);
            },
            child: const Text('Record partial release'),
          ),
          const SizedBox(height: 16),
          Text('Admin decision', style: Theme.of(context).textTheme.titleMedium),
          TextField(
            controller: decisionController,
            decoration: const InputDecoration(labelText: 'Decision'),
          ),
          TextField(
            controller: adminController,
            decoration: const InputDecoration(labelText: 'Admin name'),
          ),
          const SizedBox(height: 8),
          ElevatedButton(
            onPressed: () async {
              final escrowId = int.tryParse(escrowIdController.text);
              if (escrowId == null || decisionController.text.isEmpty || adminController.text.isEmpty) return;
              await ref.read(freelanceRepositoryProvider).recordEscrowDecision(
                    escrowId: escrowId,
                    decision: decisionController.text,
                    admin: adminController.text,
                  );
              ref.invalidate(escrowActionsProvider);
            },
            child: const Text('Submit admin decision'),
          ),
        ],
      ),
    );
  }
}
