import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../state/ads_blocs.dart';

class CreativeEditorPage extends StatefulWidget {
  const CreativeEditorPage({super.key, this.adGroupId = 0});

  static Widget builder(BuildContext context) => const CreativeEditorPage();

  final int adGroupId;

  @override
  State<CreativeEditorPage> createState() => _CreativeEditorPageState();
}

class _CreativeEditorPageState extends State<CreativeEditorPage> {
  late final TextEditingController _headline;
  late final TextEditingController _body;
  String _type = 'banner';
  String _cta = 'Learn More';

  @override
  void initState() {
    super.initState();
    _headline = TextEditingController();
    _body = TextEditingController();
    if (widget.adGroupId != 0) {
      context.read<CreativeBloc>().load(widget.adGroupId);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Creatives')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            DropdownButton<String>(
              value: _type,
              items: const [
                DropdownMenuItem(value: 'banner', child: Text('Banner')),
                DropdownMenuItem(value: 'video', child: Text('Video')),
                DropdownMenuItem(value: 'text', child: Text('Text')),
                DropdownMenuItem(value: 'search', child: Text('Search')),
              ],
              onChanged: (value) => setState(() => _type = value ?? 'banner'),
            ),
            TextField(controller: _headline, decoration: const InputDecoration(labelText: 'Headline')),
            TextField(controller: _body, decoration: const InputDecoration(labelText: 'Body')),
            TextField(
              decoration: const InputDecoration(labelText: 'Call to Action'),
              controller: TextEditingController(text: _cta),
              onChanged: (value) => _cta = value,
            ),
            const SizedBox(height: 12),
            ElevatedButton(
              onPressed: () async {
                final creative = Creative(
                  id: 0,
                  adGroupId: widget.adGroupId,
                  type: _type,
                  headline: _headline.text,
                  body: _body.text,
                  callToAction: _cta,
                );
                await context.read<CreativeBloc>().save(creative);
                if (mounted) Navigator.of(context).pop();
              },
              child: const Text('Save Creative'),
            ),
            const SizedBox(height: 24),
            Expanded(
              child: BlocBuilder<CreativeBloc, CreativeState>(
                builder: (context, state) {
                  if (state.status == CreativeStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (state.status == CreativeStatus.error) {
                    return Center(child: Text(state.error ?? 'Error'));
                  }
                  if (state.creatives.isEmpty) {
                    return const Center(child: Text('No creatives yet'));
                  }
                  return ListView(
                    children: state.creatives
                        .map(
                          (c) => ListTile(
                            title: Text('${c.type}: ${c.headline}'),
                            subtitle: Text(c.body),
                            trailing: Text(c.callToAction ?? ''),
                          ),
                        )
                        .toList(),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
