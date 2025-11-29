import 'package:flutter/material.dart';

import '../models/models.dart';

class CampaignWizardScreen extends StatefulWidget {
  const CampaignWizardScreen({super.key, this.existing});

  static const routeName = '/ads/campaigns/create';

  final Campaign? existing;

  @override
  State<CampaignWizardScreen> createState() => _CampaignWizardScreenState();
}

class _CampaignWizardScreenState extends State<CampaignWizardScreen> {
  int currentStep = 0;
  final _formKey = GlobalKey<FormState>();
  final Map<String, dynamic> form = {};

  void nextStep() {
    if (_formKey.currentState?.validate() ?? false) {
      _formKey.currentState?.save();
      if (currentStep < 4) {
        setState(() => currentStep++);
      } else {
        ScaffoldMessenger.of(context)
            .showSnackBar(const SnackBar(content: Text('Campaign submitted')));
        Navigator.of(context).pop(form);
      }
    }
  }

  void previousStep() {
    if (currentStep > 0) setState(() => currentStep--);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.existing == null ? 'Create Campaign' : 'Edit Campaign')),
      body: Form(
        key: _formKey,
        child: Stepper(
          currentStep: currentStep,
          onStepContinue: nextStep,
          onStepCancel: previousStep,
          controlsBuilder: (context, details) => Row(
            children: [
              ElevatedButton(onPressed: details.onStepContinue, child: Text(currentStep == 4 ? 'Submit' : 'Next')),
              const SizedBox(width: 8),
              if (currentStep > 0)
                TextButton(onPressed: details.onStepCancel, child: const Text('Back')),
            ],
          ),
          steps: [
            Step(
              title: const Text('Objective & Name'),
              isActive: currentStep >= 0,
              content: Column(
                children: [
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Campaign Name'),
                    initialValue: widget.existing?.name,
                    validator: (value) => value == null || value.isEmpty ? 'Required' : null,
                    onSaved: (value) => form['name'] = value,
                  ),
                  DropdownButtonFormField<String>(
                    decoration: const InputDecoration(labelText: 'Objective'),
                    items: const [
                      DropdownMenuItem(value: 'traffic', child: Text('Traffic')),
                      DropdownMenuItem(value: 'conversions', child: Text('Conversions')),
                      DropdownMenuItem(value: 'awareness', child: Text('Awareness')),
                    ],
                    validator: (value) => value == null ? 'Choose one' : null,
                    onSaved: (value) => form['objective'] = value,
                  ),
                ],
              ),
            ),
            Step(
              title: const Text('Audience'),
              isActive: currentStep >= 1,
              content: Column(
                children: [
                  Wrap(
                    spacing: 8,
                    children: ['Male', 'Female', 'Non-binary']
                        .map((g) => FilterChip(label: Text(g), selected: false, onSelected: (_) {}))
                        .toList(),
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Locations'),
                    onSaved: (value) => form['locations'] = value,
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Interests/Keywords'),
                    onSaved: (value) => form['interests'] = value,
                  ),
                ],
              ),
            ),
            Step(
              title: const Text('Placements'),
              isActive: currentStep >= 2,
              content: Column(
                children: [
                  ...['Feed', 'Profile', 'Search', 'Jobs', 'Gigs', 'Podcasts', 'Webinars', 'Networking']
                      .map((p) => CheckboxListTile(
                            value: false,
                            onChanged: (_) {},
                            title: Text(p),
                          )),
                ],
              ),
            ),
            Step(
              title: const Text('Budget & Schedule'),
              isActive: currentStep >= 3,
              content: Column(
                children: [
                  DropdownButtonFormField<String>(
                    decoration: const InputDecoration(labelText: 'Budget Type'),
                    items: const [
                      DropdownMenuItem(value: 'daily', child: Text('Daily')),
                      DropdownMenuItem(value: 'lifetime', child: Text('Lifetime')),
                    ],
                    onSaved: (value) => form['budget_type'] = value,
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Amount'),
                    keyboardType: TextInputType.number,
                    onSaved: (value) => form['amount'] = value,
                  ),
                  TextFormField(
                    decoration: const InputDecoration(labelText: 'Bidding Model'),
                    onSaved: (value) => form['bidding'] = value,
                  ),
                ],
              ),
            ),
            Step(
              title: const Text('Review'),
              isActive: currentStep >= 4,
              content: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Review your settings before submitting.'),
                  Text('Name: ${form['name'] ?? ''}'),
                  Text('Objective: ${form['objective'] ?? ''}'),
                  Text('Budget: ${form['amount'] ?? ''}'),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
