import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Textarea } from '../components/ui/textarea';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '../components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../components/ui/alert-dialog';
import { Plus, Pencil, Trash2, FileText, ExternalLink } from 'lucide-react';
import { useToast } from '../components/ui/use-toast';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const PagesPage = () => {
  const { t } = useI18n();
  const { toast } = useToast();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [formData, setFormData] = useState({
    title: '',
    content: '',
    seo_title: '',
    seo_description: '',
    status: 'draft',
    order: 0
  });

  const fetchItems = async () => {
    try {
      const response = await axios.get(`${API}/pages`);
      setItems(response.data);
    } catch (error) {
      console.error('Failed to fetch pages:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchItems();
  }, []);

  const handleOpenDialog = (item = null) => {
    if (item) {
      setSelectedItem(item);
      const contentText = item.content?.blocks?.[0]?.text || 
                          (typeof item.content === 'string' ? item.content : '');
      setFormData({
        title: item.title,
        content: contentText,
        seo_title: item.seo?.title || '',
        seo_description: item.seo?.description || '',
        status: item.status,
        order: item.order
      });
    } else {
      setSelectedItem(null);
      setFormData({
        title: '',
        content: '',
        seo_title: '',
        seo_description: '',
        status: 'draft',
        order: items.length
      });
    }
    setDialogOpen(true);
  };

  const handleSubmit = async () => {
    const payload = {
      title: formData.title,
      content: { blocks: [{ type: 'paragraph', text: formData.content }] },
      seo: { title: formData.seo_title, description: formData.seo_description },
      status: formData.status,
      order: formData.order
    };

    try {
      if (selectedItem) {
        await axios.put(`${API}/pages/${selectedItem.id}`, payload);
        toast({ title: t('save'), description: 'Pagina bijgewerkt' });
      } else {
        await axios.post(`${API}/pages`, payload);
        toast({ title: t('add'), description: 'Pagina toegevoegd' });
      }
      setDialogOpen(false);
      fetchItems();
    } catch (error) {
      console.error('Failed to save:', error);
      toast({ title: 'Error', description: 'Er ging iets mis', variant: 'destructive' });
    }
  };

  const handleDelete = async () => {
    try {
      await axios.delete(`${API}/pages/${selectedItem.id}`);
      toast({ title: t('delete'), description: 'Pagina verwijderd' });
      setDeleteDialogOpen(false);
      setSelectedItem(null);
      fetchItems();
    } catch (error) {
      console.error('Failed to delete:', error);
      toast({ title: 'Error', description: 'Er ging iets mis', variant: 'destructive' });
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-[#129387]"></div>
      </div>
    );
  }

  return (
    <div className="animate-fadeIn">
      {/* Header */}
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-white mb-2">{t('pages_title')}</h1>
          <p className="text-white/60">{t('pages_subtitle')}</p>
        </div>
        <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2" data-testid="add-page-btn">
          <Plus className="w-4 h-4" />
          {t('pages_add')}
        </Button>
      </div>

      {/* Content */}
      {items.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <FileText className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">{t('pages_empty')}</h3>
            <p className="text-white/60 mb-4">{t('pages_empty_desc')}</p>
            <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2">
              <Plus className="w-4 h-4" />
              {t('pages_add')}
            </Button>
          </CardContent>
        </Card>
      ) : (
        <Card className="dashboard-card overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full table-dark">
              <thead>
                <tr>
                  <th className="text-left p-4">{t('pages_name')}</th>
                  <th className="text-left p-4">Slug</th>
                  <th className="text-left p-4">{t('status')}</th>
                  <th className="text-right p-4">{t('actions')}</th>
                </tr>
              </thead>
              <tbody>
                {items.map((item) => (
                  <tr key={item.id} data-testid={`page-row-${item.id}`}>
                    <td className="p-4">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                          <FileText className="w-5 h-5 text-purple-400" />
                        </div>
                        <div>
                          <p className="font-medium text-white">{item.title}</p>
                          {item.seo?.title && (
                            <p className="text-xs text-white/40">{item.seo.title}</p>
                          )}
                        </div>
                      </div>
                    </td>
                    <td className="p-4">
                      <code className="text-sm text-[#129387]">/{item.slug}</code>
                    </td>
                    <td className="p-4">
                      <span className={`px-2 py-1 rounded text-xs font-medium ${item.status === 'published' ? 'badge-teal' : 'badge-draft'}`}>
                        {item.status === 'published' ? t('published') : t('draft')}
                      </span>
                    </td>
                    <td className="p-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <Button 
                          variant="ghost" 
                          size="sm" 
                          onClick={() => handleOpenDialog(item)}
                          className="text-white/60 hover:text-white hover:bg-white/5"
                          data-testid={`edit-page-${item.id}`}
                        >
                          <Pencil className="w-4 h-4" />
                        </Button>
                        <Button 
                          variant="ghost" 
                          size="sm" 
                          onClick={() => { setSelectedItem(item); setDeleteDialogOpen(true); }}
                          className="text-red-400/60 hover:text-red-400 hover:bg-red-500/10"
                          data-testid={`delete-page-${item.id}`}
                        >
                          <Trash2 className="w-4 h-4" />
                        </Button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>
      )}

      {/* Edit/Add Dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="bg-[#1c2336] border-white/10 text-white max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{selectedItem ? t('pages_edit') : t('pages_add')}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('pages_name')}</Label>
                <Input
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  className="input-dark"
                  data-testid="page-title-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('status')}</Label>
                <Select value={formData.status} onValueChange={(v) => setFormData({ ...formData, status: v })}>
                  <SelectTrigger className="input-dark" data-testid="page-status-select">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent className="bg-[#1c2336] border-white/10">
                    <SelectItem value="draft">{t('draft')}</SelectItem>
                    <SelectItem value="published">{t('published')}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('pages_content')}</Label>
              <Textarea
                value={formData.content}
                onChange={(e) => setFormData({ ...formData, content: e.target.value })}
                className="input-dark min-h-32"
                data-testid="page-content-input"
              />
            </div>
            <div className="border-t border-white/10 pt-4">
              <h4 className="text-sm font-medium text-white/60 mb-3">SEO</h4>
              <div className="space-y-4">
                <div className="space-y-2">
                  <Label className="text-white/80">{t('pages_seo_title')}</Label>
                  <Input
                    value={formData.seo_title}
                    onChange={(e) => setFormData({ ...formData, seo_title: e.target.value })}
                    className="input-dark"
                    placeholder="Titel voor zoekmachines"
                    data-testid="page-seo-title-input"
                  />
                </div>
                <div className="space-y-2">
                  <Label className="text-white/80">{t('pages_seo_description')}</Label>
                  <Textarea
                    value={formData.seo_description}
                    onChange={(e) => setFormData({ ...formData, seo_description: e.target.value })}
                    className="input-dark"
                    placeholder="Beschrijving voor zoekmachines"
                    data-testid="page-seo-description-input"
                  />
                </div>
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setDialogOpen(false)} className="border-white/10 text-white hover:bg-white/5">
              {t('cancel')}
            </Button>
            <Button onClick={handleSubmit} className="btn-primary" data-testid="page-save-btn">
              {t('save')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Delete Confirmation */}
      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent className="bg-[#1c2336] border-white/10">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-white">{t('delete')}</AlertDialogTitle>
            <AlertDialogDescription className="text-white/60">
              {t('pages_delete_confirm')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="border-white/10 text-white hover:bg-white/5">{t('cancel')}</AlertDialogCancel>
            <AlertDialogAction onClick={handleDelete} className="bg-red-500 hover:bg-red-600">
              {t('delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default PagesPage;
