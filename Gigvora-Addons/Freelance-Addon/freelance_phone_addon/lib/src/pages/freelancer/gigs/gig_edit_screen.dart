import 'package:flutter/material.dart';

class GigEditScreen extends StatefulWidget {
  const GigEditScreen({super.key});

  @override
  State<GigEditScreen> createState() => _GigEditScreenState();
}

class _GigEditScreenState extends State<GigEditScreen> {
  int step = 0;
  final PageController _controller = PageController();
  final _formKey = GlobalKey<FormState>();

  void next() {
    if (step < 4) {
      setState(() => step++);
      _controller.animateToPage(step, duration: const Duration(milliseconds: 200), curve: Curves.easeInOut);
    }
  }

  void prev() {
    if (step > 0) {
      setState(() => step--);
      _controller.animateToPage(step, duration: const Duration(milliseconds: 200), curve: Curves.easeInOut);
    }
  }

  @override
  Widget build(BuildContext context) {
    final steps = ['Overview', 'Pricing', 'Description', 'Requirements', 'Preview'];
    return Scaffold(
      appBar: AppBar(title: const Text('Gig Wizard')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [Text('Step ${step + 1} of 5'), Text(steps[step])],
            ),
          ),
          Expanded(
            child: Form(
              key: _formKey,
              child: PageView(
                controller: _controller,
                physics: const NeverScrollableScrollPhysics(),
                children: const [
                  _OverviewStep(),
                  _PricingStep(),
                  _DescriptionStep(),
                  _RequirementsStep(),
                  _PreviewStep(),
                ],
              ),
            ),
          ),
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                children: [
                  TextButton(onPressed: prev, child: const Text('Back')),
                  const Spacer(),
                  OutlinedButton(onPressed: () {}, child: const Text('Save & Exit')),
                  const SizedBox(width: 8),
                  ElevatedButton(onPressed: next, child: Text(step == 4 ? 'Publish' : 'Next')),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}

class _OverviewStep extends StatelessWidget {
  const _OverviewStep();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: const [
        TextField(decoration: InputDecoration(labelText: 'Title')),
        SizedBox(height: 12),
        TextField(decoration: InputDecoration(labelText: 'Category')),
        SizedBox(height: 12),
        TextField(decoration: InputDecoration(labelText: 'Tags')),
      ],
    );
  }
}

class _PricingStep extends StatelessWidget {
  const _PricingStep();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        _packageCard('Basic'),
        _packageCard('Standard'),
        _packageCard('Premium'),
      ],
    );
  }

  Widget _packageCard(String label) => Card(
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            children: [
              Text(label, style: const TextStyle(fontWeight: FontWeight.bold)),
              TextField(decoration: const InputDecoration(labelText: 'Price')),
              TextField(decoration: const InputDecoration(labelText: 'Delivery time (days)')),
              TextField(decoration: const InputDecoration(labelText: 'Revisions')),
            ],
          ),
        ),
      );
}

class _DescriptionStep extends StatelessWidget {
  const _DescriptionStep();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const TextField(maxLines: 6, decoration: InputDecoration(labelText: 'Description')),
        const SizedBox(height: 12),
        ElevatedButton.icon(onPressed: () {}, icon: const Icon(Icons.add), label: const Text('Add FAQ')),
      ],
    );
  }
}

class _RequirementsStep extends StatelessWidget {
  const _RequirementsStep();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        const TextField(decoration: InputDecoration(labelText: 'Requirement 1')),
        const SizedBox(height: 12),
        OutlinedButton.icon(onPressed: () {}, icon: const Icon(Icons.add), label: const Text('Add requirement')),
      ],
    );
  }
}

class _PreviewStep extends StatelessWidget {
  const _PreviewStep();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: const [
        ListTile(title: Text('Gig preview title'), subtitle: Text('Description and FAQs preview')),
        ListTile(title: Text('Fee breakdown'), subtitle: Text('Commission shown here')),
      ],
    );
  }
}
