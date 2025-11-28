import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../config.dart';
import '../../services/api_client.dart';
import '../../services/billing_service.dart';
import '../../state/billing_state.dart';

class EmployerBillingScreen extends StatelessWidget {
  const EmployerBillingScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => BillingState(
        BillingService(
          JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()),
        ),
      )..load(),
      child: Scaffold(
        appBar: AppBar(title: const Text('Billing')),
        body: Consumer<BillingState>(
          builder: (context, state, _) {
            final plan = state.currentPlan;
            return ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (plan != null)
                  Card(
                    child: ListTile(
                      title: Text(plan.name),
                      subtitle: Text('Credits: ${plan.creditsRemaining ?? plan.jobsIncluded}'),
                      trailing: Text('USD ${plan.price.toStringAsFixed(2)}'),
                    ),
                  ),
                const SizedBox(height: 12),
                Text('Plans', style: Theme.of(context).textTheme.titleMedium),
                ...state.plans.map(
                  (p) => ListTile(
                    title: Text(p.name),
                    subtitle: Text('${p.jobsIncluded} jobs · ${p.durationDays} days'),
                    trailing: ElevatedButton(
                      onPressed: () => state.choosePlan(p.id),
                      child: const Text('Choose'),
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Text('Invoices', style: Theme.of(context).textTheme.titleMedium),
                ...state.invoices.map(
                  (invoice) => ListTile(
                    title: Text(invoice.description),
                    subtitle: Text(invoice.date.toLocal().toString()),
                    trailing: Text(invoice.status),
                  ),
                ),
              ],
            );
          },
        ),
      ),
    );
  }
}
