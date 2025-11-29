import 'package:flutter/material.dart';

class FreelanceOnboardingScreen extends StatefulWidget {
  const FreelanceOnboardingScreen({super.key});

  @override
  State<FreelanceOnboardingScreen> createState() => _FreelanceOnboardingScreenState();
}

class _FreelanceOnboardingScreenState extends State<FreelanceOnboardingScreen> {
  bool asFreelancer = true;
  bool asClient = false;
  final _formKey = GlobalKey<FormState>();
  String skills = '';
  String rate = '';
  String headline = '';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Freelance Setup')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            const Text('Start freelancing or hire talent', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            SwitchListTile(
              value: asFreelancer,
              title: const Text('I want to work as a freelancer'),
              onChanged: (v) => setState(() => asFreelancer = v),
            ),
            SwitchListTile(
              value: asClient,
              title: const Text('I want to hire freelancers'),
              onChanged: (v) => setState(() => asClient = v),
            ),
            const SizedBox(height: 12),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Skills'),
              onChanged: (v) => skills = v,
              validator: (v) => (asFreelancer && (v?.isEmpty ?? true)) ? 'Required for freelancers' : null,
            ),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Hourly rate'),
              keyboardType: TextInputType.number,
              onChanged: (v) => rate = v,
            ),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Headline'),
              onChanged: (v) => headline = v,
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                if (!_formKey.currentState!.validate()) return;
                ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Onboarding saved')));
              },
              child: const Text('Continue'),
            )
          ],
        ),
      ),
    );
  }
}
