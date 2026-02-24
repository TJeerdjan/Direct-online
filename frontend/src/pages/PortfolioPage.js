import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Textarea } from '../components/ui/textarea';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '../components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../components/ui/alert-dialog';
import { Plus, Pencil, Trash2, Briefcase, ExternalLink } from 'lucide-react';
import { useToast } from '../hooks/use-toast';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const PortfolioPage = () => {
  const { t } = useI18n();
  const { toast } = useToast();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    client_name: '',
    category: '',
    images: '',
    tags: '',
    status: 'draft',
    order: 0
  });

  const fetchItems = async () => {
    try {
      const response = await axios.get(`${API}/portfolio`);
      setItems(response.data);
    } catch (error) {
      console.error('Failed to fetch portfolio:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchItems();
  }, []);

  const handleOpenDialog = (item = null) => {
    if (item) {
      setSelectedItem(item);
      setFormData({
        title: item.title,
        description: item.description,
        client_name: item.client_name,
        category: item.category,
        images: item.images.join('\n'),
        tags: item.tags.join(', '),
        status: item.status,
        order: item.order
      });
    } else {
      setSelectedItem(null);
      setFormData({
        title: '',
        description: '',
        client_name: '',
        category: '',
        images: '',
        tags: '',
        status: 'draft',
        order: items.length
      });
    }
    setDialogOpen(true);
  };

  const handleSubmit = async () => {
    const payload = {
      ...formData,
      images: formData.images.split('\n').filter(url => url.trim()),
      tags: formData.tags.split(',').map(tag => tag.trim()).filter(Boolean)
    };

    try {
      if (selectedItem) {
        await axios.put(`${API}/portfolio/${selectedItem.id}`, payload);
        toast({ title: t('save'), description: 'Project bijgewerkt' });
      } else {
        await axios.post(`${API}/portfolio`, payload);
        toast({ title: t('add'), description: 'Project toegevoegd' });
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
      await axios.delete(`${API}/portfolio/${selectedItem.id}`);
      toast({ title: t('delete'), description: 'Project verwijderd' });
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
          <h1 className="text-3xl font-bold text-white mb-2">{t('portfolio_title')}</h1>
          <p className="text-white/60">{t('portfolio_subtitle')}</p>
        </div>
        <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2" data-testid="add-portfolio-btn">
          <Plus className="w-4 h-4" />
          {t('portfolio_add')}
        </Button>
      </div>

      {/* Content */}
      {items.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <Briefcase className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">{t('portfolio_empty')}</h3>
            <p className="text-white/60 mb-4">{t('portfolio_empty_desc')}</p>
            <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2">
              <Plus className="w-4 h-4" />
              {t('portfolio_add')}
            </Button>
          </CardContent>
        </Card>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {items.map((item) => (
            <Card key={item.id} className="dashboard-card overflow-hidden group" data-testid={`portfolio-item-${item.id}`}>
              {item.images[0] && (
                <div className="aspect-video bg-[#0e172c] relative overflow-hidden">
                  <img 
                    src={item.images[0]} 
                    alt={item.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div className="absolute top-2 right-2">
                    <span className={`px-2 py-1 rounded text-xs font-medium ${item.status === 'published' ? 'badge-teal' : 'badge-draft'}`}>
                      {item.status === 'published' ? t('published') : t('draft')}
                    </span>
                  </div>
                </div>
              )}
              <CardContent className="p-4">
                <h3 className="text-lg font-semibold text-white mb-1">{item.title}</h3>
                {item.client_name && (
                  <p className="text-[#129387] text-sm mb-2">{item.client_name}</p>
                )}
                <p className="text-white/60 text-sm line-clamp-2 mb-4">{item.description}</p>
                <div className="flex items-center gap-2">
                  <Button 
                    variant="outline" 
                    size="sm" 
                    onClick={() => handleOpenDialog(item)}
                    className="flex-1 border-white/10 text-white hover:bg-white/5"
                    data-testid={`edit-portfolio-${item.id}`}
                  >
                    <Pencil className="w-4 h-4 mr-1" />
                    {t('edit')}
                  </Button>
                  <Button 
                    variant="outline" 
                    size="sm" 
                    onClick={() => { setSelectedItem(item); setDeleteDialogOpen(true); }}
                    className="border-red-500/20 text-red-400 hover:bg-red-500/10"
                    data-testid={`delete-portfolio-${item.id}`}
                  >
                    <Trash2 className="w-4 h-4" />
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}

      {/* Edit/Add Dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="bg-[#1c2336] border-white/10 text-white max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{selectedItem ? t('portfolio_edit') : t('portfolio_add')}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('portfolio_name')}</Label>
                <Input
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  className="input-dark"
                  data-testid="portfolio-title-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('portfolio_client')}</Label>
                <Input
                  value={formData.client_name}
                  onChange={(e) => setFormData({ ...formData, client_name: e.target.value })}
                  className="input-dark"
                  data-testid="portfolio-client-input"
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('portfolio_category')}</Label>
                <Input
                  value={formData.category}
                  onChange={(e) => setFormData({ ...formData, category: e.target.value })}
                  className="input-dark"
                  placeholder="Fotografie, Design, etc."
                  data-testid="portfolio-category-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('status')}</Label>
                <Select value={formData.status} onValueChange={(v) => setFormData({ ...formData, status: v })}>
                  <SelectTrigger className="input-dark" data-testid="portfolio-status-select">
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
              <Label className="text-white/80">{t('portfolio_description')}</Label>
              <Textarea
                value={formData.description}
                onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                className="input-dark min-h-24"
                data-testid="portfolio-description-input"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('portfolio_images')} (één URL per regel)</Label>
              <Textarea
                value={formData.images}
                onChange={(e) => setFormData({ ...formData, images: e.target.value })}
                className="input-dark min-h-20"
                placeholder="https://example.com/image1.jpg&#10;https://example.com/image2.jpg"
                data-testid="portfolio-images-input"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('portfolio_tags')} (gescheiden door komma's)</Label>
              <Input
                value={formData.tags}
                onChange={(e) => setFormData({ ...formData, tags: e.target.value })}
                className="input-dark"
                placeholder="fotografie, portret, studio"
                data-testid="portfolio-tags-input"
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setDialogOpen(false)} className="border-white/10 text-white hover:bg-white/5">
              {t('cancel')}
            </Button>
            <Button onClick={handleSubmit} className="btn-primary" data-testid="portfolio-save-btn">
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
              {t('portfolio_delete_confirm')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="border-white/10 text-white hover:bg-white/5">{t('cancel')}</AlertDialogCancel>
            <AlertDialogAction onClick={handleDelete} className="bg-red-500 hover:bg-red-600" data-testid="confirm-delete-btn">
              {t('delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
};

export default PortfolioPage;
