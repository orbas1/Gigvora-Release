import 'package:flutter/material.dart';

import '../models/models.dart';

class CreativeEditScreen extends StatefulWidget {
  const CreativeEditScreen({super.key, this.creative});

  static const routeName = '/ads/creatives/edit';

  final Creative? creative;

  @override
  State<CreativeEditScreen> createState() => _CreativeEditScreenState();
}

class _CreativeEditScreenState extends State<CreativeEditScreen> {
  final _formKey = GlobalKey<FormState>();
  String type = 'text';
  String headline = '';
  String description = '';
  String url = '';

  @override
  void initState() {
    super.initState();
    if (widget.creative != null) {
      type = widget.creative!.type;
      headline = widget.creative!.headline;
      description = widget.creative!.description;
      url = widget.creative!.url;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Creative Editor')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              DropdownButtonFormField<String>(
                value: type,
                items: const [
                  DropdownMenuItem(value: 'text', child: Text('Text')),
                  DropdownMenuItem(value: 'banner', child: Text('Banner')),
                  DropdownMenuItem(value: 'video', child: Text('Video')),
                  DropdownMenuItem(value: 'search', child: Text('Search')),
                ],
                onChanged: (value) => setState(() => type = value ?? 'text'),
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Headline'),
                initialValue: headline,
                validator: (v) => v == null || v.isEmpty ? 'Required' : null,
                onSaved: (v) => headline = v ?? '',
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Description'),
                maxLines: 3,
                initialValue: description,
                onSaved: (v) => description = v ?? '',
              ),
              TextFormField(
                decoration: const InputDecoration(labelText: 'Destination URL'),
                initialValue: url,
                validator: (v) => v == null || v.isEmpty ? 'Required' : null,
                onSaved: (v) => url = v ?? '',
              ),
              const SizedBox(height: 16),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Preview', style: TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 8),
                      Text(headline.isEmpty ? 'Your headline' : headline),
                      Text(description.isEmpty ? 'Ad description' : description),
                      Text(url.isEmpty ? 'https://example.com' : url),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 8),
              ElevatedButton(
                onPressed: () {
                  if (_formKey.currentState?.validate() ?? false) {
                    _formKey.currentState?.save();
                    ScaffoldMessenger.of(context)
                        .showSnackBar(const SnackBar(content: Text('Creative saved')));
                    Navigator.of(context).pop();
                  }
                },
                child: const Text('Save Creative'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
